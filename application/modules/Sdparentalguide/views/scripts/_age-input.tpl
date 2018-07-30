    

<div class="container p-0" id="family-information"> 
    
    <div class="row add-child-form add-age-range d-block mx-0 pb-4 border-0">
        <div class="add-gender age-range p-0 mb-4" id="add-gender">
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('18', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('Under 18'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('18-24', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('18-24'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('25-34', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('25-34'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('35-44', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('35-44'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('45-54', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('45-54'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('55-64', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('55-64'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items age-range-set px-2">
                <a href="javascript:void(0)" onclick="selectAgeRange('65', this)" class="d-block w-100 py-4">
                    <?php echo $this->translate('65+'); ?>
                </a>
            </div>
            <input type="hidden" name="profile_age_range_set" id="profile_age_range_set" value=""/>
            <input type="hidden" name="<?php echo $this->name; ?>" id="<?php echo $this->name; ?>" class="field_container">
        </div>
    </div> <!-- end of add-gender -->
</div>

<script>

function selectAgeRange(type, e) {

    document.getElementById('profile_age_range_set').value = type;
    document.getElementById('<?php echo $this->name; ?>').value = type;

    let addAgeRangeSetItems = document.getElementsByClassName('add-gender-items');
    for(var i = 0; i < addAgeRangeSetItems.length; i++) {
        addAgeRangeSetItems[i].classList.remove('selected-age');
    }
    e.getParent().classList.add('selected-age');
}

en4.core.runonce.add(function() {

    let addAgeRangeSetItems = document.getElementsByClassName('add-gender-items age-range-set');

    let currentAgeSet = '<?php echo $this->subject()->gg_age_range; ?>';

    for(var i = 0; i < addAgeRangeSetItems.length; i++) {

        let ageSet = addAgeRangeSetItems[i].getElement('a').textContent.replace(/\s+/g, " ").trim();

        if(ageSet == '65+') { ageSet = '65'; } else if (ageSet == 'Under 18') { ageSet = '18'; }
        
        if(ageSet == currentAgeSet) {
            addAgeRangeSetItems[i].classList.add('selected-age');
            document.getElementById('profile_age_range_set').value = type;
            document.getElementById('<?php echo $this->name; ?>').value = type;
        }
    }

});

</script>