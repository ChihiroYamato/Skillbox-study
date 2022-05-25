const formContainer = {
    selectors: {
        validation: '.needs-validation',
    },
    classes: {
        validated: 'was-validated',
    }
};

const Button = {
    edit: '[edit]',
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

    $(Button.edit).on('click', event => {
        event.preventDefault();
        event.stopPropagation();

        let target = $(event.target);
        event.target.textContent = 'Save';

        target.removeAttr('edit');
        target.attr('type', 'submit');
        target.off('click');

        let div = target.closest('.form-edit').find('div.display-active');
        let input = target.closest('.form-edit').find('input.display-none')

        div.removeClass('display-active');
        div.addClass('display-none');
        input.removeClass('display-none');
        input.addClass('display-active');
    });
});
