jQuery(document).ready(function($) {
   $(document).tooltip({
       position: {
           my: "center top",
           at: "center bottom"
       },
       items: "[data-raid]",
       content: function() {
           var element = $(this);
           var output = '';
           if (element.is("[data-raid]")) {
               var raidData = element.data('raid');
               output += '<div class="raid-members">'
               for (var i in raidData) {
                   output += '<div class="raid-member-columns raid-' + i + '">';
                   output += '<h4>' + i.toUpperCase() + '</h4>';
                   for (var k in raidData[i]) {
                       var member = raidData[i][k];
                       output += '<div class="raid-member">';
                       output += '<span class="person">' + member['person'] + '</span><span class="status ' + member['status'] + '">' + member['status'] + '</span>'
                       output += '</div>';
                   }
                   output += '</div>';
               }
               output += "</div>";

               return output;
           }
       }
   });
});