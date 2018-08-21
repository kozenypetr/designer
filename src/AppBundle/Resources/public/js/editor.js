var frameEditor = {
    selector: ".frame",
    box: null,

    init: function() {
        frameEditor.box = $(frameEditor.selector);
        if (frameEditor.box.length)
        {
            frameEditor.initEvent();
        }
    },

    initEvent: function()
    {
        $('#attribute .frame-title').keyup(frameEditor.updateTitle).keyup();
        $('#attribute .frame-top-text').keyup(frameEditor.updateTopText).keyup();
        $('#attribute .frame-bottom-text').keyup(frameEditor.updateBottomText).keyup();
    },

    updateTitle: function(e)
    {
        frameEditor.box.find('.title').text($(this).val());
    },

    updateTopText: function()
    {
        frameEditor.box.find('.top-text').text($(this).val());
    },

    updateBottomText: function()
    {
        frameEditor.box.find('.bottom-text').text($(this).val());
    },

    /*readSettings: function() {
        console.log( myFeature.settings );
    }*/
};

$(function() {
    frameEditor.init();
})