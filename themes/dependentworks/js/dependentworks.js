jQuery(document).ready(function() {
    var recordId;
    var pathParts = window.location.href.split('/');
    var recordIndex = pathParts.length - 1;
    if (pathParts[recordIndex] == 'Record') {
      recordId = pathParts[(recordIndex + 1)];
    } else {
      recordIndex--;
      if (pathParts[recordIndex] == 'Record') {
        recordId = pathParts[(recordIndex + 1)];
      } else {
        recordId = 0;
      }
    }

    jQuery.ajax({
        url:'/vufind/DependentWorksAjax/JSON?method=getDependentWorks',
        dataType:'json',
        data:{ppn:recordId},
        success:function(data, textStatus) {
            if (data.data.length > 0) {
                jQuery('div#DependentWorks').append('<ul id="DependentWorks"></ul>');
                for (var i = 0; i < data.data.length; i++) {
                    var title = data.data[i]['title'] + ' (' + data.data[i]['publishDate'] + ')';
                    var href = '<a href="/vufind/Record/' + data.data[i]['id'] + '" target="_blank">' + title + '</a>';
                    jQuery('ul#DependentWorks').append('<li>' + href + '</li>');                
                }
                jQuery('div#DependentWorks').attr('style', 'display:block');
            }
        }
    });
});
