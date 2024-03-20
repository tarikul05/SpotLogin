<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('category')->get();
        return view('pages.faqs.index', compact('faqs'));
    }

    public function tutos()
    {
        $categories = Category::all();
        $faqs = Faq::with('category')->get();
        return view('pages.faqs.tutos', compact('faqs', 'categories'));
    }

    public function tutosShow(Faq $faq)
    {
        return view('pages.faqs.show-tutos', compact('faq'));
    }

    public function add(Request $request)
    {
        $videoDirectory = public_path('videos');

        if (file_exists($videoDirectory)) {
            $videos = [];
            $files = $this->getMP4Files($videoDirectory);
            foreach ($files as $file) {
                $videos[] = str_replace($videoDirectory . '/', '', $file);
            }
        }
        $categories = Category::all();
        return view('pages.faqs.form', compact('categories', 'videos'));
    }

    public function edit(Faq $faq)
    {
        $videoDirectory = public_path('videos');

        if (file_exists($videoDirectory)) {
            $videos = [];
            $files = $this->getMP4Files($videoDirectory);
            foreach ($files as $file) {
                $videos[] = str_replace($videoDirectory . '/', '', $file);
            }
        }
        $categories = Category::all();
        return view('pages.faqs.form', compact('faq', 'categories', 'videos'));
    }

    public function show(Faq $faq)
    {
        return view('pages.faqs.show', compact('faq'));
    }

    public function create(Request $request)
    {
        $data = $request->all();

        Faq::create($data);

        return redirect()->route('faqs.list')->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'description' => 'nullable|string',
            'youtube_link' => 'required',
        ]);

        $faq->update($data);

        return redirect()->route('faqs.list')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('faqs.list')->with('success', 'FAQ deleted successfully.');
    }

    private function getMP4Files($directory)
    {
        $files = [];
    
        // Récupérer la liste des fichiers dans le répertoire
        $dirContents = scandir($directory);
    
        // Parcourir les fichiers et les dossiers
        foreach ($dirContents as $item) {
            // Exclure les dossiers spéciaux
            if ($item !== '.' && $item !== '..') {
                // Construire le chemin complet de l'élément
                $itemPath = $directory . DIRECTORY_SEPARATOR . $item;
    
                // Vérifier si l'élément est un fichier
                if (is_file($itemPath)) {
                    // Vérifier si le fichier est un fichier .mp4
                    if (pathinfo($itemPath, PATHINFO_EXTENSION) === 'mp4') {
                        // Ajouter le chemin complet du fichier à la liste des vidéos
                        $files[] = $itemPath;
                    }
                } elseif (is_dir($itemPath)) {
                    // Si l'élément est un dossier, obtenir récursivement les fichiers à l'intérieur
                    $files = array_merge($files, $this->getMP4Files($itemPath));
                }
            }
        }
    
        return $files;
    }

}
