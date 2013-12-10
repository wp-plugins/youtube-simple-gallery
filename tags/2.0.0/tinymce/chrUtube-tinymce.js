(function() {
    tinymce.create('tinymce.plugins.CHR.Utube', {
        init : function(ed, url) {
            ed.addButton('showUtube', {
                title : 'YouTube Simple Gallery',
                image : url + '/icon-youtube.png',
                onclick : function() {
                    tb_show("Insert YouTube Simple Gallery", url+"/../tinymce/chrUtube-tinymce-page.php?a=a&width=670&height=400");
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname : "YouTube Simple Gallery",
                author : 'CHR Designer',
                authorurl : 'http://www.chrdesigner.com/',
                infourl : 'http://www.chrdesigner.com/blog/',
                version : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('chrUtube', tinymce.plugins.CHR.Utube);
})();