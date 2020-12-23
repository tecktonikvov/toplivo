function viewDivHeadWrap(){
  document.getElementById("wrap_form").style.display = "block";
  document.getElementById("blur_bg").style.display = "block";
  document.getElementById("blur_bg") ['style'] ['z-index'] = "1";
  document.getElementById("blur_bg").style.filter = "blur(15px)";
}

function hideDiv(){
  document.getElementById("wrap_form").style.display = 'none';
  document.getElementById("blur_bg").style.filter = "";
  document.getElementById("blur_bg").style.display = "none";
  document.getElementById("blur_bg") ['style'] ['z-index'] = "-1";
}

function changeColor(brand){
  let chkbox = document.getElementById(brand);
  let element = document.getElementById('span_' + brand);

  if (chkbox.checked){
     element.classList.add("brand_selected");
  }
  else {
    element.classList.remove("brand_selected");
  }
}

function selectAllBrands(){
  let spanAllBrands = document.getElementsByClassName('span_brand');
  let chkAll = document.getElementById('chk_all');
  
  if (chkAll.checked){
        for (let i = 0; i< spanAllBrands.length; i++){
        spanAllBrands[i].classList.add("brand_selected");
      }
  }else{
    for (let i = 0; i< spanAllBrands.length; i++){
      spanAllBrands[i].classList.remove("brand_selected");
    }
  }
}

function func() {
  window.location.href = 'redirect-url';
}
