//��ѡ��ʾ
function fooo( _e ){ 
if( _e.checked ){ 
document.all.d.style.display = ""; 
}else{ 
document.all.d.style.display = "none"; 
} 
} 
//��ʾ��
function showDiv(){
document.getElementById('popDiv').style.display='block';
document.getElementById('bg').style.display='block';
}
function closeDiv(){
document.getElementById('popDiv').style.display='none';
document.getElementById('bg').style.display='none';
}