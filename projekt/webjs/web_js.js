window.onload = gallery;
//when we load your gallery in browser then gallery function is loaded
function gallery(){
     // gallery is the name of function
     var allimages = document.images;
     //now we create a variable allimages
     //and we store all images in this allimages variable
     for(var i=0; i<allimages.length; i++){
          //now we create a for loop
          if(allimages[i].id.indexOf("small") > -1){
               allimages[i].onclick = imgChanger;
               //in above line we are adding a listener to all the thumbs stored inside the allimages array on onclick event.
          }
     }
}
//declaring the imgChanger function
function imgChanger(){
     //changing the src attribute value of the large image.
     document.getElementById('myPicture').src ="images/"+this.id+"-big.jpg";
}