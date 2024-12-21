<?php
$catalogs = $pdo->query("SELECT * FROM catalog")->fetchAll(PDO::FETCH_ASSOC);


?>
<section class="product__info">
            <h2 class="show-product-text text-center m-3 shadow-sm p-2">Danh mục sản phẩm</h2>
            <div class="info--item d-flex justify-content-around" id="slider">  
                <?php foreach ($catalogs as $catalog) : ?>
                        <a class="text-center" href="../public/catalog.php?catalog_id=<?php echo $catalog['catalog_id']; ?>">
                            <img src="<?php echo $catalog['img']; ?>" width="100px" height="80px" alt="" draggable="false">
                            <p class="dropdown-item text-dark" ><?= $catalog['catalogName'] ?></p></li>
                        </a>  
                <?php endforeach; ?>  
                                    
            </div>
                
        </section>

<script>

let sliderWidth = slider.scrollWidth -slider.clientWidth;
function autoPlay() {
    if (slider.scrollLeft > (sliderWidth - 1)){
        slider.scrollLeft -=sliderWidth;
    }else{
        slider.scrollLeft +=1
    }
} 
let play = setInterval( autoPlay, 100);

let isDragging = false, startX, startScrollLeft;

const dragStart =(e) => {
    isDragging=true;
    slider.classList.add("dragging");
    startX =e.pageX;
    startScrollLeft = slider.scrollLeft;
}
const dragging = (e) => {
    /*console.log(e.pageX);*/
    if(!isDragging) return;
    slider.scrollLeft= startScrollLeft - (e.pageX - startX);
}
const dragStop = () =>{
    isDragging =false;
    slider.classList.remove("dragging");
}
slider.addEventListener("mousedown",dragStart);
slider.addEventListener("mousemove",dragging);
document.addEventListener("mouseup",dragStop);
</script>
<style>
    .info--item{
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }
    .info--item::-webkit-scrollbar {
      display: none;
  }
</style>


