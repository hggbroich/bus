let buttons = document.querySelectorAll('[data-maps-endpoint]');

for(let button of buttons) {
    button.addEventListener('click', (e) => {
        e.preventDefault();

        let schoolField = document.getElementById(button.getAttribute('data-school-field-id'));

        let url = button.getAttribute('data-maps-endpoint');

        if(schoolField !== null) {
            url += '?schoolId=' + schoolField.value;
        }

        window.open(url, '_blank');
    })
}
