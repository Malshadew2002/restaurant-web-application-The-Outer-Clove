//navbar
window.addEventListener('scroll', function(){
let navbar = document.getElementById("navbar");
navbar.classList.toggle('fixed', this.window.scrollY > 0)
})

//nav buttons
let menuBtn = document.querySelector('.menu-btn');
let cartBtn = document.querySelector('.cartbtn');
let darkBtn = document.querySelector('.darkbtn');

menuBtn.onclick = function(){
    document.getElementById("nav-items").classList.toggle('active');

//change icon on click
    if(document.getElementById("nav-items").classList.contains('active')){
        menuBtn.classList.remove("bx-menu");
        menuBtn.classList.add("bx-x")
    }
    else{
        menuBtn.classList.remove("bx-x");
        menuBtn.classList.add("bx-menu");
    }
}



cartBtn.onclick = function(){
    document.getElementById("cart").classList.toggle('active');

//change icon on click
    if(document.getElementById("cart").classList.contains('active')){
        cartBtn.classList.remove("bx-cart");
        cartBtn.classList.add("bx-x")
    }
    else{
        cartBtn.classList.remove("bx-x");
        cartBtn.classList.add("bx-cart");
    }
}

darkBtn.onclick = function(){
    document.body.classList.toggle('dark-mode');

//change mode on click
    if(document.body.classList.contains('dark-mode')){
        darkBtn.classList.remove("bx-moon");
        darkBtn.classList.add("bx-sun")
    }
    else{
        darkBtn.classList.remove("bx-sun");
        darkBtn.classList.add("bx-moon");
    }
}

//menu section
let menuTabs = Document.querySelector('.menu-tabs');
menuTabs.addEventListener('click', function(e){
if(e.target.classList.contains('menu-tab-item') && !e.target.classList.contains('active')){
    menuTabs.querySelector('.active').classList.remove('active');
    e.target.classList.add("active");
}else{
    return
}
})

//login page

const wrapper = document.querySelector('.wrapper');

function registerActive() {
    wrapper.classList.toggle('active');
}

function loginActive() {
    wrapper.classList.toggle('active');
}

//---admin page-------//

// add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};