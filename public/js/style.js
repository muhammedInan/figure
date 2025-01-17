function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);
}

var $collectionHolder;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="add_tag_link">Ajout image</button>');

var $newImageLinkLi = $('<li></li>').append($addTagButton);
var $addVideoButton = $('<button type="button" class="add_video_link">Ajout video</button>');
var $newVideoLinkLi = $('<li></li>').append($addVideoButton);

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.tags');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newImageLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagButton.on('click', function (e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newImageLinkLi);
    });
    // Get the ul that holds the collection of tags
    $videosHolder = $('ul.videos');

    // add the "add a tag" anchor and li to the tags ul
    $videosHolder.append($newVideoLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $videosHolder.data('index', $videosHolder.find(':input').length);

    $addVideoButton.on('click', function (e) {
        // add a new tag form (see next code block)
        addTagForm($videosHolder, $newVideoLinkLi);
    });
    if ($('.videos').is(':empty')) {
        addTagForm($videosHolder, $newVideoLinkLi);
    }

});