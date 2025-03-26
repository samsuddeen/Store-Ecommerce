<template>
    <div>
        <div class="form-group">
            <h2>Service and Delivery</h2>
            <div class="form-group">
                <label for="">Warranty Type </label>
                <select name="warranty_type" id="warranty_type" v-model="type" class="form-control form-control-sm" @click="changeSection()">
                    <option value="No Warranty">No Warranty</option>
                    <option value="Local Seller Warranty">Local Seller Warranty</option>
                    <option value="International Warranty">International Warranty</option>
                    <option value="100 % Original Product">100 % Original Product</option>
                    <option value="100 % Original Product">100 % Original Product</option>
                    <option value="Brand Warranty">Brand Warranty</option>
                    <option value="Seller Warranty">Seller Warranty</option>
                </select>
            </div>
             <div class="form-group" v-if="warrentyPeriod">
                <label for="">Warranty Period</label>
                <select name="warranty_period" id="warranty_period" class="form-control form-control-sm">
                    <option value="">Please Select</option>
                    <option v-for="i in 18" :value="i+' Months'">{{i+ " Months"}}</option>
                    <option v-for="i in 30" :value="i+' Years'">{{i+ " Years"}}</option>
                    <option :value="'Life Time'">Life Time</option>
                </select>
            </div>
            <div class="form-group" v-if="warrentyPolicy">
                <label for="warranty_policy">Warranty Policy</label>
                <textarea name="warranty_policy" id="warranty_policy" rows="5" class="form-control form-control-sm"></textarea>
            </div>
            <h2>Delivery</h2>
            <div class="form-group">
                <label for="package_weight">Shipping Charge</label>
                <input type="number" name="shipping_charge" value="0" required class="form-control form-control-sm">
            </div>
            <div class="form-group">
                <label for="package_weight">Packaged Weight (In number (Kg))</label>
                <input type="number" name="package_weight" required class="form-control form-control-sm">
            </div>
            <div class="form-group">
                <label for="package_dimension">Package Dimension</label>
                <input type="text" class="" name="dimension_length" placeholder="Lenght (Inch)">
                <input type="text" class="" name="dimension_width" placeholder="Width (Inch)">
                <input type="text" class="" name="dimension_height" placeholder="Height (Inch)">
            </div>
            <div class="form-group">
                <label for="dangerous_good">
                    Dangerous:
                </label>
                <label for="">
                    <input type="checkbox" value="Battery" name="dangerous_good[]">Battery
                </label>
                <label for="">
                    <input type="checkbox" value="Flammable" name="dangerous_good[]">Flammable
                </label>
                <label for="">
                    <input type="checkbox" value="Liquid" name="dangerous_good[]">Liquid
                </label>
                 <label for="">
                    <input type="checkbox" value="None" name="dangerous_good[]">None
                </label>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            type:null,
            warrentyPeriod:true,
            warrentyPolicy:true,
        }
    },
    methods:{
        changeSection(){
            const warrentyType = this.type;
            if (warrentyType === 'No Warranty') {
                this.warrentyPeriod = false;
                this.warrentyPolicy = false;
            }
            else {
                this.warrentyPeriod = true;
                this.warrentyPolicy = true;
            }
        }
    }
};
</script>