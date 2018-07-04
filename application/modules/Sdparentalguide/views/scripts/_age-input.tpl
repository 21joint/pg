    

<div class="container p-0" id="family-information"> 
    
    <div class="row add-child-form add-another-child d-block mx-0 pb-4 border-0">
        <div class="col-12 d-flex add-gender add-range p-0 mb-4" id="add-gender"> 
            <div class="col-2 mr-1 p-0 text-center age small add-gender-items ">
                <a href="javascript:void(0)" onclick="selectAge(1, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('Under 18'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center age small add-gender-items">
                <a href="javascript:void(0)" onclick="selectAge(2, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('18-24'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center age small add-gender-items">
                <a href="javascript:void(0)" onclick="selectAge(3, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('25-34'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center age small add-gender-items ">
                <a href="javascript:void(0)" onclick="selectAge(4, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('35-44'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center age small add-gender-items ">
                <a href="javascript:void(0)" onclick="selectAge(5, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('45-54'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center age small add-gender-items">
                <a href="javascript:void(0)" onclick="selectAge(6, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('55-64'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center age small add-gender-items ">
                <a href="javascript:void(0)" onclick="selectAge(7, this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('65+'); ?>
                </a>
            </div>
        </div>
    </div> <!-- end of add-gender -->
</div>

<script>

function selectAge(type, e) {
    let addGenderItems = document.getElementsByClassName('add-gender-items');
    for(var i = 0; i < addGenderItems.length; i++) {
        addGenderItems[i].classList.remove('selected-age');
    }
    e.getParent().classList.add('selected-age');
}

</script>