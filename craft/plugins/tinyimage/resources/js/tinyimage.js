// perform on click action for optimize buttons
$('.tinyimage-optimize-asset').on('click', function(event) {
    // prevent the page from reloading
    event.preventDefault();
    // get the image id
    var image = $(this).attr('data-asset-id');
    // hide the button
    $(this).hide();
    // find the parent and show the image
    $(this).parent('td').find("img").show();
    // identify the tr to
    var row = $(this).closest("tr");
    // hide the ignore button
    row.find(".tinyimage-ignore-image").hide();
    // optimize the image
    optimizeAsset(image, row);
});

// perform on click action for ignore buttons
$('.tinyimage-ignore-asset').on('click', function(event) {
    // prevent the page from reloading
    event.preventDefault();
    // get the image id
    var image = $(this).attr('data-asset-id');
    // hide the button
    $(this).hide();
    // find the parent and show the asset
    $(this).parent('td').find("img").show();
    // identify the tr to
    var row = $(this).closest("tr");
    // hide the optimize button
    row.find(".tinyimage-optimize-image").hide();
    // ignore the asset
    ignoreAsset(image, row);
});

// perform on click action for removing an asset ignore
$('.tinyimage-unignore-asset').on('click', function(event) {
    // prevent the page from reloading
    event.preventDefault();
    // get the image id
    var image = $(this).attr('data-asset-id');
    // hide the button
    $(this).hide();
    // find the parent and show the asset
    $(this).parent('td').find("img").show();
    // identify the tr to
    var row = $(this).closest("tr");
    // remove the asset from the ignore list
    removeAssetIgnore(image, row);
});

// Javascript for sources

// perform on click action for optimize source
$('.tinyimage-optimize-source').on('click', function(event) {
    // prevent the page from reloading
    event.preventDefault();
    // get the image id
    var source = $(this).attr('data-source-id');
    // hide the button
    $(this).hide();
    // find the parent and show the image
    $(this).parent('td').find("img").show();
    // identify the tr to
    var row = $(this).closest("tr");
    // hide the ignore button
    row.find(".tinyimage-ignore-source").hide();
    // optimize the image
    optimizeSource(source, row);
});

// optimize the image
function optimizeAsset(asset, row) {

    var data = {'asset': asset};

    // post to the controller
    Craft.queueActionRequest('tinyImage/optimizeImage', data, $.proxy(function(response)
    {
        if (response.success == true) {
            Craft.cp.displayNotice('Added image optimization task');
            // hide the row
            row.hide();
        } else {
            Craft.cp.displayError(response.message);
            // reset the buttons
            resetElements(row);
        };

    }, this));
}

// optimize a source
function optimizeSource(source, row) {

    var data = {'source': source};

    // post to the controller
    Craft.queueActionRequest('tinyImage/optimizeSource', data, $.proxy(function(response)
    {
        if (response.success == true) {
            Craft.cp.displayNotice('Added optimize source task');
            // hide the row
            row.hide();
        } else {
            Craft.cp.displayError(response.message);
            // reset the buttons
            resetElements(row);
        };

    }, this));
}

// ignore an asset
function ignoreAsset(asset, row) {

    var data = {'asset': asset};

    // post to the controller
    Craft.queueActionRequest('tinyImage/ignore', data, $.proxy(function(response)
    {
        if (response.success == true) {
            Craft.cp.displayNotice('Ignored the image!');
            // hide the row
            row.hide();
        } else {
            Craft.cp.displayError(response.message);
            // reset the buttons
            resetElements(row);
        };

    }, this));
}

// remove asset ignore
function removeAssetIgnore(asset, row) {

    var data = {'asset': asset};

    // post to the controller
    Craft.queueActionRequest('tinyImage/unignore', data, $.proxy(function(response)
    {
        if (response.success == true) {
            Craft.cp.displayNotice('Removed the image from the ignore list!');
            // hide the row
            row.hide();
        } else {
            Craft.cp.displayError(response.message);
            // reset the buttons
            resetIgnoreElements(row);
        };

    }, this));
}

// perform a reset
function resetElements(row) {
    row.find("img").hide();
    row.find(".tinyimage-optimize-image").show();
    row.find(".tinyimage-ignore-image").show();
}

// perform a reset
function resetIgnoreElements(row) {
    row.find("img").hide();
    row.find(".tinyimage-unignore-image").show();
}
