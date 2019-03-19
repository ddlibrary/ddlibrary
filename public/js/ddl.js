
if(window.jQuery){
    $(document).ready(function(){
        $('.add_more').click(function(e){
            e.preventDefault();
            $(this).before("<br><input name='attachments[]' type='file'/>");
        });

        $('.fa-share-square').click(function(e){
            $('#shareModal').show();
        });

        $('#share-close').click(function(e){
            $('#shareModal').hide();
        });

        $('#favorite-close').click(function(e){
            $('#favoriteModal').hide();
        });

        $('.fa-flag').click(function(e){
            $('#flagModal').show();
        });

        $('#flag-close').click(function(e){
            $('#flagModal').hide();
        });

        $('#survey-close').click(function(e){
            $('#surveyModal').hide();
        });

        $('input[type="checkbox"]').click(function(e){
            $('#side-submit').show();
        });

        //for accordions in the resourcelist
        var acc = document.getElementsByClassName("accordion");
        var i;
        for (i = 0; i < acc.length; i++) {	
            acc[i].addEventListener("click", function() {	
            this.classList.toggle("active");	
            var panel = this.nextElementSibling;	

             if (panel.style.maxHeight){	
                panel.style.maxHeight = null;	
            } else {	
                //adding 500 to just give extra max-height. 
                panel.style.maxHeight = panel.scrollHeight+500 + "px";	
            } 	
            });	
        }
        $('#resource-subjects').trigger('click');
    });
}

function openNav() {
    document.getElementById("myNav").style.width = "100%";
}

function closeNav() {
    document.getElementById("myNav").style.width = "0%";
}

function favorite(elementId, baseUrl, resourceId, userId) 
{
    let csrf = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: "POST",
        url: baseUrl,
        data: {resourceId : resourceId, userId : userId, _token : csrf }, // appears as $_GET['id'] @ your backend side
        success: function(data) {
            console.log(data);
            var obj = JSON.parse(data);
            // data is ur summary
            if(obj == "added"){
                $('#'+elementId).addClass("active");  
            }else if(obj == "deleted"){
                $('#'+elementId).removeClass("active");     
            }else if(obj == "notloggedin"){
                $('#favoriteModal').show();
            }
        }
    });
}

function showHide(itself, elementId)
{
    var theElement = document.getElementById(elementId);

    if (theElement.style.display === "none") {
        theElement.style.display = "block";
    } else {
        theElement.style.display = "none";
    }

    if (itself.className.indexOf("js-fa-plus") == -1) {  
        itself.className += " js-fa-plus";
    } else { 
        itself.className = itself.className.replace(" js-fa-plus", " fa-minus");
    }
}

function fnTest(check, cchild){
    if($(check).is(':checked')){
        $(check).siblings('#'.cchild).find('.js-child').prop("checked",true);
    }else{
        $(check).siblings('#'.cchild).find('.js-child').prop("checked",false);        
    }
}

function changeContent(DivContent, methodUrl, parameters)
{
    $.ajax({
        type: "POST",
        url: methodUrl,
        data: "id=" + id, // appears as $_GET['id'] @ your backend side
        success: function(data) {
            // data is ur summary
            $('#'+DivContent).html(data);
        }
    });
}

function populate(element, targetId, targetContent)
{
    var selectedOption = element.options[element.selectedIndex].value;
    var targetLocation = document.getElementById(targetId);
    var textInput = document.getElementById('js-text-city');

    if(selectedOption == 256){
        textInput.style.display = 'none';
        var i;
        for (i = 0; i < targetContent.length; i++) {
            var item = new Option(targetContent[i].name, targetContent[i].tnid);
            targetLocation.options.add(item);
        }
        targetLocation.style.display = 'block';
    }else{
        targetLocation.style.display = 'none';
        textInput.style.display = 'block';
        
    }
}

function split( val ) {
    return val.split( /,\s*/ );
}
function extractLast( term ) {
    return split( term ).pop();
}

function bringMeAttr(id, url)
{
    $( "#"+id )
    // don't navigate away from the field on tab when selecting an item
    .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
        event.preventDefault();
        }
    })
    .autocomplete({
        source: function( request, response ) {
            $.getJSON( url, {
                term: extractLast( request.term )
            }, response );
        },
        search: function() {
            // custom minLength
            var term = extractLast( this.value );
            if ( term.length < 2 ) {
                return false;
            }
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function( event, ui ) {
            var terms = split( this.value );
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push( ui.item.value );
            // add placeholder to get the comma-and-space at the end
            terms.push( "" );
            this.value = terms.join( ", " );
            return false;
        }
    });
}