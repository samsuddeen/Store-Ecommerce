<template>
  <div>
    <product-image :lfmId="-1" :name="'image_name'" :product_images="this.product_images"></product-image>
    <Category :product_id="this.product_id"/>

  </div>
</template>

<script>
import ProductImage from "./products/ProductImage.vue";
import Category from "../components/Category.vue";
export default {
    name: "ProductForm",
    props:{
      product_id:Number,
    },
    components: { ProductImage, Category },
    data(){
        return{
           product_images:"",
        };
    },
    created(){
      // console.log(this.product_id);
      // this.getFeaturedImages()
    },
    mounted(){

    },
    methods:{
      getFeaturedImages(){
          axios.get(window.BASE_URL+'/get/product/image/'+this.product_id)
          .then((response)=>{
            var images = response.data
            var image="";
            images.forEach(element => {
              if(images.length == 0){
                  image = element.image;
              }else{
                  image = element.image + ",";
              }
            });
            this.product_images = image;
          })
          .catch((error)=>{
            console.log(error);
          });
      },
    }
};
</script>
