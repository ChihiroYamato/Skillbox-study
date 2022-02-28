const button = {
    callOrder: '[call-order]',
    learnMore: '[learn-more]',
    getSort: '[get-sort]',
};

const elements = {
    sort: '[sorting-elements]'
};

const classes = {
    body: {
        fixed: 'fixed-content',
    },
    form: {
        container: 'form-container',
        content: 'form-content',
    },
};

$( () => {
    $(button.callOrder).on('click', () => {
        $.get('./form.html')
        .done(response => {
            $('body').addClass(classes.body.fixed);

            const container = document.createElement('div');
            container.className = classes.form.container;

            const form = document.createElement('div');
            form.className = classes.form.content;

            $(form).prepend(response);
            $(container).prepend(form);
            $('body').prepend(container);

            $(container).on('click', event => {
                if (event.target == container) {
                    $(container).remove();
                    $('body').removeClass(classes.body.fixed)
                }
            });
        })
        .fail(error => {
            console.log(error);
        });
    });

    $(button.getSort).on('click', () => {
        const elementsList = $(elements.sort).children();
        shuffle(elementsList);

        $(elements.sort).empty();

        for (let i = 0; i < elementsList.length; i++) {
            $(elements.sort).append(elementsList[i]);
        }
    });

    $(button.learnMore).on('click', event => {
        $(event.target).css('position', 'relative');
        let hideMod = false;

        if (rand(0, 1)) {
            hideMod = true;
            $(event.target).animate({opacity: 0}, 500);
        }

        $(event.target).animate({
            left: (rand(0, 1) ? '+' : '-') + '=' + rand(200, 500),
            top: (rand(0, 1) ? '+' : '-') + '=' + rand(20, 100),
        }, {
            duration: hideMod ? 0 : 1000,
            complete: () => {
                const parent = event.target.parentElement.parentElement;
                let leftOffset = $(event.target).offset().left;
                let topOffset = $(event.target).offset().top;
                if (leftOffset < 0
                    || leftOffset > $(window).width()
                    || topOffset < $(parent).offset().top
                    || topOffset > ($(parent).offset().top + $(parent).height())
                ) {
                    $(event.target).css('left', '0px');
                    $(event.target).css('top', '0px');
                }
            }
        });

        if (hideMod) {
            hideMod = false;
            $(event.target).animate({opacity: 1}, 500);
        }
    });
});

function rand(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
      let j = rand(0, i);

      [array[i], array[j]] = [array[j], array[i]];
    }
}
