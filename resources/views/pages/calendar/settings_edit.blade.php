<!-- Formulaire pour éditer les paramètres du calendrier -->
<form action="{{ route('calendar.settings.update') }}" method="post">
    @csrf
    @method('PUT')
    <label for="timezone">Timezone:</label>
    <input type="text" name="timezone" value="{{ $timezone }}">
    <br>
    <label for="minTime">MinTime:</label>
    <input type="text" name="minTime" value="{{ $minTime }}">
    <br>
    <label for="maxTime">MaxTime:</label>
    <input type="text" name="maxTime" value="{{ $maxTime }}">
    <br>
    <button type="submit">Enregistrer</button>
</form>
