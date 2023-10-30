


var tableContent = 'You need to setup the price of your new categories for use it on schedule. Do you want to setup the prices of your new categories now ?<br><br><table class="table table-bordered table-hover"><thead><tr><th>New Category</th><th>Action</th></tr></thead><tbody>';
newCategories.forEach(function(category) {
    var categoryName = category.name;
    var categoryColor = category.bg_color_agenda;
    var categoryBadge = '<span class="badge" style="width: 60px; heigth:60px; background-color: ' + categoryColor + '"></span>';
    var actionButton = '<button class="btn btn-primary" onclick="activateTab2(\'' + categoryName + '\')">Setup prices</button>';
    tableContent += '<tr><td>' + categoryBadge + ' ' + categoryName + '</td><td>' + actionButton + '</td></tr>';
});

tableContent += '</tbody></table>';
Swal.fire({
    title: 'New category',
    text: 'Do you want to setup the prices of your new category ?',
    icon: 'question',
    showCancelButton: true,
    cancelButtonText: "No, Thanks !",
    confirmButtonText: 'Go to prices',
    html: tableContent,
    preConfirm: () => {
        console.log('c ok!');
        var tab2 = new bootstrap.Tab(document.getElementById('nav-prices-tab'));
        tab2.show();
    }
});

function activateTab2(categoryName) {
    var tab2 = new bootstrap.Tab(document.getElementById('nav-prices-tab'));
    tab2.show();
    Swal.close();

        var accordionElements = document.querySelectorAll('.accordion-collapse');
        for (var i = 0; i < accordionElements.length; i++) {
            var accordionElement = accordionElements[i];
            if (accordionElement.getAttribute('data-category-id') === categoryName) {
                var accordion = new bootstrap.Collapse(accordionElement, {
                    toggle: true
                });
                accordion.show();

                accordionElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'start',
                    inlineToTop: true
                });
                window.scrollTo(0, accordionElement);

            }
        }
}
