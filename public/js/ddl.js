function showHide(itself, elementId)
{
    var theElement = document.getElementById(elementId);

    if (theElement.style.display === "none") {
        theElement.style.display = "block";
    } else {
        theElement.style.display = "none";
    }

    if (itself.className.indexOf("fa-plus") == -1) {  
        itself.className += " fa-plus";
    } else { 
        itself.className = itself.className.replace(" fa-plus", " fa-minus");
    }
}

function fnTest(check, cchild){
    if($(check).is(':checked')){
        $(check).siblings('#'.cchild).find('.child').prop("checked",true);
    }else{
        $(check).siblings('#'.cchild).find('.child').prop("checked",false);        
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
    var cityContainer = document.getElementById('city-container');
    var textInput = document.getElementById('text-city');

    if(selectedOption == 2657 || selectedOption == 3110 || selectedOption == 3111){
        textInput.style.display = 'none';
        var i;
        for (i = 0; i < targetContent.length; i++) {
            var item = new Option(targetContent[i].name, targetContent[i].tid);
            targetLocation.options.add(item);
        }
        targetLocation.style.display = 'block';
    }else{
        targetLocation.style.display = 'none';
        textInput.style.display = 'block';
        
    }
}