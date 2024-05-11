var i = 0;
var images = [];
var time = 3000;

// Image list
images[0] = 'images/info.jpg';
images[1] = 'images/info1.jpg';
images[2] = 'images/info2.png';
images[3] = 'images/info3.jpg';

function preloadImages(imagePaths) {
    for (var j = 0; j < imagePaths.length; j++) {
        var img = new Image();
        img.src = imagePaths[j];
    }
}
preloadImages(images);

// Function to change image
function changeImg() {
    console.log("Changing image");
    
    var imgElement = document.querySelector('img[name="slide"]');
    
    if (imgElement) {
        imgElement.src = images[i];
        console.log("Image changed to: " + images[i]);

        if (i < images.length - 1) {
            i++;
        } else {
            i = 0;
        }
        
        setTimeout(changeImg, time);
    } else {
        console.error("Image element not found.");
    }
}

window.onload = function() {
    console.log("Window loaded. Starting slideshow...");
    changeImg();
};
