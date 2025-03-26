<template>
  <div class="row">
    <h1>Price & Stock</h1>
    <a href="#" v-on:click="addColorImage()">Add Color Image</a>
    <p>Tip: Add variants when the product have different versions, such as color and size.</p>
    <div class="row color-image" v-for="(c_family, index) in this.colorFamily" :key="index">
      <label for="">
          <a href="#" v-on:click="removeColorImage(index)" class="mr-2">Remove </a>
          <a href="#">Collapse</a>
      </label>
      <div class="form-group">
        <label for="">Color Family</label>
        <select name="image_color[]" id="image_color" v-model="c_family.color" class="form-control form-control-sm" v-on:change="addColorImage1(c_family.color,index)">
          <option v-for="(color, i) in this.colors" :value="color.id" :key="i">
            {{ color.title }}
          </option>
        </select>
      </div>
      <product-image :lfmId="index" :name="'image_name'+index"></product-image>
    </div>
    <!-- <file-manager></file-manager> -->
    <div class="col-md-6">
        <div class="mb-1 dash-radio">
            <label class="form-label" for="product_for"><strong>Product For</strong>:</label>
            <input type="radio" value="1" name="product_for">Customer
            <input type="radio" value="2" name="product_for">Retailer
            <input type="radio" value="3" name="product_for" checked>Both


        </div>
    </div>
    <div class="col-md-12">
      <label for="color"> *</label>
      <a href="#" v-on:click="addRow()">Add New Sku</a>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Availability</th>
              <th>color_text</th>
              <th v-for="(attribute, index) in this.attributes">{{ attribute.title }}</th>
              <th>Customer Price*</th>
              <th>Wholesale Price*</th>
              <th>Special Price (Rs)</th>
              <th>Quantity</th>
              <th>Minimum Quantity(For WholeSale)</th>
              <th>SellerSKU</th>
              <th>Free Items</th>
              <th>Additional Charge</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(stock, index) in this.stockes" :key="index">
              <td>
                <input type="checkbox" value="1" checked v-model="this.stockes.availability">
              </td>
              <td>
                <select name="color[]" id="" class="form-control form-control-sm" v-model="stock.color">
                  <option v-for="(color, index) in this.selectedColorShow" :key="index" :value="color.id">{{ color.title }}
                  </option>
                </select>
              </td>
              <td v-for="(attribute, i) in this.attributes" :key="i">
                <input type="text" name="key[]" :value="attribute.id" hidden>
                <select name="attributes[]" id="" class="form-control" style="width: 100px;">
                  <option v-for="(value, j) in attribute.value.split(',')" :key="j" :value="value">{{ value }}</option>
                </select>

              </td>
              <td>
                <input type="number" class="form-control form-control-sm " name="price[]" v-model="stock.price" required >
              </td>
              <td>
                <input type="number" class="form-control form-control-sm " name="wholesaleprice[]" v-model="stock.wholesaleprice" required >
              </td>
              <td>
                <input type="text" name="special_price[]"  v-model="stock.special_price" :id="'special_price' + index"
                  style="display: none;">
                <input type="text" name="special_from[]" v-model="stock.special_from" style="display: none;">
                <input type="text" name="special_to[]" v-model="stock.special_to" style="display: none;">

                <label for="special_price" id="special_price">{{ stock.special_price }}</label>
                <a href="#" v-on:click="setSpecialPrice(index)"> <i class="fa fa-edit"></i>Edit</a>
              </td>
              <td>
                <input type="text" name="quantity[]" v-model="stock.quantity" class="form-control form-control-sm">
              </td>
              <td>
                <input type="number" name="mimquantity[]" v-model="stock.mimquantity" class="form-control form-control-sm" required >
              </td>
              <td>
                <input type="text" name="sellersku[]" v-model="stock.sellersku" class="form-control form-control-sm">
              </td>
              <td>
                <input type="text" name="free_items[]" v-model="stock.free_items" class="form-control form-control-sm">
              </td>
              <td>
                <input type="number" name="additional_charge[]" v-model="stock.additional_charge" class="form-control form-control-sm">
              </td>
              <td>
                <a href="#" v-on:click="deleteRow(index)"><i class="fa fa-trash"></i>Delete</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="price-modal" v-if="this.showModal">
      <div class="card card-body">
        <h6>Special Price</h6>
        <div class="form-group">
          <label for="">Choose Range (From)</label>
          <input type="date" v-model="this.start" class="form-control form-control-sm" id="start-date">
        </div>
        <div class="form-group">
          <label for="">Choose Range (To)</label>
          <input type="date" v-model="this.end" class="form-control form-control-sm" id="end-date">
        </div>
        <div class="form-group">
          <label for="price">Price:</label>
          <input type="number" v-model="this.price" class="form-control form-control-sm specialDataPrice" id="special_price">
        </div>
        <div class="form-group mt-2">
          <button type="button" v-on:click="closeSpecialPrice()" class="btn btn-info btn-sm">Confirm</button>
        </div>
      </div>
    </div>
    
  </div>
</template>
<script>
import axios from "axios";
import ProductImage from "./ProductImage.vue";
export default {
  name: "ProductPrice",
  components: { ProductImage },
  created() {
    this.getColors();
    this.getStockAttributes();
  },
  data() {
    return {
      colors: null,
      show: false,
      rows: 1,
      colorFamily: [
        {
          color:this.colors, 
        }
      ],
      stockes: [
        {
          availability: null,
          color: this.colors,
          price: null,
          wholesaleprice:null,
          quantity: null,
          mimquantity: null,
          sellersku: null,
          free_items: null,
          additional_charge:null
        }
      ],
      attributes: [],
      showModal: false,
      start: null,
      end: null,
      price: null,
      indexing: 0,
      generatedValue:[],
      selectedColorData:[],
      selectedColorShow:[],
    };
  },
  methods: {
    getValue(id, value){
        var result = id+':'+value;
        // var result = "{'"+title+"':'"+value+"'}";
        return result;
    },
    getColors() {
      axios
        .get(window.BASE_URL + "/get-colors")
        .then((response) => {
          this.colors = response.data;
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.load = false;
        });
    },
    getStockAttributes() {
      axios.get(window.BASE_URL + '/get-attributes/' + window.CATEGORY_ID)
        .then((response) => {
          console.log(response);
          this.attributes = response.data;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.load = false;
        });
    },
    setShow(index) {
      this.show = true;
      this.rows = index + 2;
      console.log(this.rows);
    },
    getSelectedColors() {
      const params = {
        colorId: this.selectedColorData
      };
     axios
       .get(window.BASE_URL + "/selected-get-colors/",{params})
       .then((response) => {
         this.selectedColorShow = response.data;
         console.log(response.data);
       })
       .catch((error) => {
         console.log(error);
       })
       .finally(() => {
         this.load = false;
       });
   },
    addColorImage() {
      this.colorFamily.push({
        color: this.colors,
      });
    },
    addColorImage1(selectedColorValue=null,colorIndex=null) {
      // this.colorFamily.push({
      //   color: this.colors,
      // });
     
      if(selectedColorValue!==null && colorIndex!==null)
      {
        if (colorIndex >= 0 && colorIndex <= this.selectedColorData.length) {
          this.selectedColorData[colorIndex]=selectedColorValue;
        }
        this.getSelectedColors();
      }
      // this.$nextTick(() => {
      //       $(".select2").select2();
      //     });
    },
    removeColorImage(index) {
      this.colorFamily.splice(index, 1);
      this.selectedColorData.splice(index,1);
      this.getSelectedColors();
    },
    addRow() {
      this.stockes.push({
        availability: null,
        color: this.colors,
        price: null,
        wholesaleprice: null,
        quantity: null,
        sellersku: null,
        free_items: null,
        additional_charge:null
      });
    },
    deleteRow(index) {
      this.stockes.splice(index, 1);
    },
    setSpecialPrice(index) {
      this.start = this.stockes[index].special_from;
      this.end = this.stockes[index].special_to;
      this.price = this.stockes[index].special_price;
      this.indexing = index;
      this.showModal = true;
    },
    closeSpecialPrice() {
      this.stockes[this.indexing].special_from = this.start;
      this.stockes[this.indexing].special_to = this.end;
      this.stockes[this.indexing].special_price = this.price;
      this.start = null;
      this.end = null;
      this.price = null;
      this.showModal = false;
    },
  },
};
</script>
<style scoped>
.price-modal {
  transition: 1.05ms;
  position: fixed;
  top: 35%;
  left: 45%;
  z-index: 900;
  overflow: hidden;
  overflow-x: hidden;
  height: auto;
  width: 273px;
  background-color: aqua;
}

.card-body {
  background-color: aqua;
}

.filemanager-modal {
  transition: 1.05ms;
  position: fixed;
  top: 10%;
  left: 45%;
  z-index: 900;
  overflow: hidden;
  overflow-x: hidden;
  height: auto;
  width: 40%;
  background-color: aqua;
}
</style>
