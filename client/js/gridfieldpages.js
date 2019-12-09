(function($) {
  $.entwine("ss", function($) {

    $(".ss-gridfield-orderable tbody").entwine({

      onadd: function(){
        this._super();

        var self = this;
        this.on('mouseup', '.col-reorder .handle', function(e){
          // store the last dragged ID to be able to get it from the reload function
          self.getGridField().data('reordered-id', $(this).parents('tr').data('id'));
        });
      },

      // We want to post to the CMSMain reorder url (/admin/pages/edit/savetreenode?SecurityID=...)
      // instead of the GridFieldExtensions reload one (/admin/pages/edit/EditForm/[page-id]/field/[field-name]/reorder"),
      // so all pages dont get 'edited' (just the one we dragged).
      // Required POST data for savetreenode: ID (reordered item), ParentID & SiblingIDs (incl reordered item)
      reload: function (ajaxOpts, successCallback) {
        // Forward to _super (eg restore the regular reorder action) -> we don't, but could this way:
        // return this._super(ajaxOpts, successCallback);

        var self = this;
        var form = this.closest('form');
        form.addClass('loading');

        // ajaxOpts.url += '?SecurityID=' + form.find('input[name="SecurityID"]').val();
        var url = this.getGridField().data('url-pagereorder');
        url += '?SecurityID=' + form.find('input[name="SecurityID"]').val();
        // console.log(ajaxOpts.url);

        // var alldata = form.find(':input').serializeArray();
        var data = {
          ParentID: form.find('input[name="ID"]').val(), // this is actually the ID of the Parent/Holder page
          // ParentID: form.find('input[name="ParentID"]').val(), // the ID of the parent's parent...
          ID: this.getGridField().data('reordered-id'),
          // SiblingIDs: form.find('input[name*="[GridFieldEditableColumns]').serializeArray(),
          SiblingIDs: this.getGridField().find('tbody tr[data-id]').map(function () {
            return this.getAttribute('data-id');
          }).get(),
        };

        // now basically just send a POST with the data
        $.post(url, data, function(result){
          // and update the modified part (TreeTitle coincidentally gets sent back, update it)
          // { modified: { 10: { TreeTitle: "..." } } }
          var modifiedId = Object.keys(result.modified)[0];
          var newTreeTitleHolderAsHtml = $('<span class="status-gfpage-extension">' + result.modified[modifiedId].TreeTitle + '</span>' );
          newTreeTitleHolderAsHtml.find('.badge').each(function () {
            newTreeTitleHolderAsHtml.addClass( this.className.replace('badge', '') );
          });
          self.find('tr[data-id="'+modifiedId+'"] .col-TreeTitleAsHtml').html( newTreeTitleHolderAsHtml );
          form.removeClass('loading');
        });
      }

    });

  });
})(jQuery);