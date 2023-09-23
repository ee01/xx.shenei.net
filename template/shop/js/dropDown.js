function getObject(objectId) { 
 if(document.getElementById && document.getElementById(objectId)) { 
 return document.getElementById(objectId); 
 } 
 else if (document.all && document.all(objectId)) { 
 return document.all(objectId); 
 } 
 else if (document.layers && document.layers[objectId]) { 
 return document.layers[objectId]; 
 } 
 else { 
 return false; 
 } 
} 

function showHide(e,objname){     
    var obj = getObject(objname); 
    if(obj.style.display == "none"){ 
        obj.style.display = "block"; 
        e.className="xias"; 
    }else{ 
        obj.style.display = "none"; 
        e.className="rights"; 
    }
}