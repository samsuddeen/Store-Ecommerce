<style>
    .product-color.active{
        border: 1px solid #000000;
        /* display: flex; */
        padding: 3px;
    }
    .appendFlex{
        display: flex;
    }
</style>
<div id="attribute">
    @php
        $colorBlocks = '';
        foreach ($colorData as $colors) {
            $colorBlocks .=
                '<a href="javascript:;" style="margin-right:10px;font-size:20px;border-radius:100%; " class="'.($colors['color_code']==$colorCodeValue ? "active ":"").'changeColor product-color changeColorAttribute" data-colorCode="' .
                $colors['color_code'] .
                '">
                    <span title="' .
                    $colors['title'] .
                    '" class="product-color" style="background-color:' .
                    $colors['color_code'] .
                    ';border:' .
                    $colors['color_code'] .
                    ';padding-left:20px; border-radius:100%;">
                    </span>
                </a>';
        }
    
        $attributeRows = '';
            $attributeRows.= '<div class="d-flex justify-content-start" style="gap:40px;padding-top:20px;">';
        foreach ($stocks as $keys) {
            // Filter attributes related to the current key
            $attributesForKey = array_filter($attributeData['attributes'], function($varient) use ($keys) {
                return $varient['title'] == $keys['title'];
            });
            $attributeCount = count($attributesForKey);
    
            $attributeRows .=
                '<div id="ul-relate-to-attribute">
                    <div >
                        <div >
                            <div class="details-more-side text-center" style="text-transform:uppercase; font-weight:600;">
                                <input type="hidden" name="key_title[]" value="' .
                                    $keys['title'] .
                                '">
                                <input type="hidden" name="key[]" value="' .
                                    $keys['id'] .
                                '">
                                <p>' .
                                    $keys['title'] .
                                '</p>
                            </div>
                        </div>
                        <div >
                            <div class="form-group single-prop-det">';
    
            $firstVariantFound = false;
            foreach ($attributeData['attributes'] as $varient) {
                if ($keys['title'] == $varient['title']) {
                    $borderColor = !$firstVariantFound ? 'red' : 'black';
                    $firstVariantFound = true;
    
                    if ($attributeCount > 1) {
                        $attributeRows .=
                            '<a href="javascript:;" data-totalQuantity="'.$varient['totalQty'].'"
                                class="me-2 btn btn-link btn-fcs updateAttribute selecteField' .
                                $varient['stock_id'] .
                                '"
                                data-productPrice="' .
                                $varient['price'] .
                                '" data-productId="' .
                                $productId .
                                '"
                                data-stockId="' .
                                $varient['stock_id'] .
                                '" data-colorCode="' .
                                $varient['color_code'] .
                                '"
                                style="text-decoration: none; border: 2px solid ' .
                                $borderColor .
                                ';">
                                <span style="color:#000000; font-weight:500;">' .
                                $varient['value'] .
                                '</span>
                            </a>';
                    } else {
                        $attributeRows .=
                            '<span class="btn btn-link btn-fcs" style="border: 2px solid; color:#000000; font-weight:500;  ' .
                            $borderColor .
                            ';">
                                ' .
                                $varient['value'] .
                                '
                            </span>';
                    }
                    $attributeRows .= '<input type="text" name="value[]" hidden="">';
                }
            }
    
            $attributeRows .= '
                            </div>
                        </div>
                    </div>
                </div>';
        }
        $attributeRows.= '</div>';
    @endphp
    <div class="attributeColor">
        <div class="attributeColor" style="display:{{count($colorData) > 1 ? 'flex appendFlex':''}};gap:20px">
            {!! $colorBlocks !!}
        </div>
        
        {!! $attributeRows !!}
    </div>
    </div>
    