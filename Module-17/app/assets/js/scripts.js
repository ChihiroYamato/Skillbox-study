const formContainer = {
    selectors: {
        validation: '.needs-validation',
    },
    classes: {
        validated: 'was-validated',
    }
};

$( () => {
    let forms = $(formContainer.selectors.validation);

    forms.each( index => {
        let form = forms[index];

        $(form).on('submit', event => {
            if (! form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add(formContainer.classes.validated);
        });
    });
});
