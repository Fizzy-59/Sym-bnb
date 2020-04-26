$('#add-image').click(function ()
{
    // Recupére le numéros de futures champs créé
    const index = +$('#widgets-counter').val();

    // Recupe le prototype des entrées
    const tmpl = $("#ad_images").data('prototype').replace(/__name__/g, index);

    // J'injecte ce code au sein de la div
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    // Gère le boutton supprimer
    handleDeleteButtons();

});

function handleDeleteButtons()
{
    $('button[data-action="delete"]').click(function ()
    {
        const target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}

updateCounter();
handleDeleteButtons();