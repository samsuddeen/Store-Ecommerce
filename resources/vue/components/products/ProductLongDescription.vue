<template>
  <!-- <div class="row"> -->
    <div class="col-12">
      <h4 class="card-title">{{this.title}}</h4>
      <div class="form-floating mb-0">
        <textarea
          data-length="2000"
          class="form-control char-textarea long-description"
          id="textarea-counter1"
          rows="3"
          placeholder="Description"
          :name="this.name"
          style="height: 200px"
        ></textarea>
        <label for="textarea-counter"></label>
      </div>
      <small class="textarea-counter-value float-start"></small>
      <small class="textarea-counter-value float-end"
        ><span class="char-count">{{ description.length }}</span> / {{this.char_length}}
      </small>
    </div>
  <!-- </div> -->
</template>
<script>
import { ref } from "@vue/reactivity";
export default {
  name: "ProductDescription",
  props: [
    "description",
    'title',
    "name", 
    "char_length",
  ],
  setup(props) {
    var total = ref(0);
    var description = ref(props.description);
    return { total, description };
  },
  created(){
    
  },
  mounted(){
    console.log(this.name);
    console.log(this.title);
    console.log(this.char_length);
    loadCKEditor2();
  },  
};
</script>
