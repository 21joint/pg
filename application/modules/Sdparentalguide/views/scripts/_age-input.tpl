    

<div class="container p-0" id="family-information"> 
    
    <div class="row add-child-form add-age-range d-block mx-0 pb-4 border-0">
        <div class="add-gender age-range p-0 mb-4" id="add-gender">
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('18', this)" class="d-block w-100 py-4">
                    <?= $this->translate('Under 18'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('18-24', this)" class="d-block w-100 py-4">
                    <?= $this->translate('18-24'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('25-34', this)" class="d-block w-100 py-4">
                    <?= $this->translate('25-34'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('35-44', this)" class="d-block w-100 py-4">
                    <?= $this->translate('35-44'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('45-54', this)" class="d-block w-100 py-4">
                    <?= $this->translate('45-54'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('55-64', this)" class="d-block w-100 py-4">
                    <?= $this->translate('55-64'); ?>
                </a>
            </div>
            <div class="text-center age small add-gender-items px-2">
                <a href="javascript:void(0)" onclick="selectAge('65', this)" class="d-block w-100 py-4">
                    <?= $this->translate('65+'); ?>
                </a>
            </div>
            <input type="hidden" name="profile_age_range" id="profile_age_range" value=""/>
            <input type="hidden" name="<?= $this->name; ?>" id="<?= $this->name; ?>" class="field_container">
        </div>
    </div> <!-- end of add-gender -->
</div>

<script>

function selectAge(type, e) {

    document.getElementById('profile_age_range').value = type;
    document.getElementById('<?= $this->name; ?>').value = type;

    let addAgeRangeItems = document.getElementsByClassName('add-gender-items');
    for(var i = 0; i < addAgeRangeItems.length; i++) {
        addAgeRangeItems[i].classList.remove('selected-age');
    }
    e.getParent().classList.add('selected-age');
}

en4.core.runonce.add(function() {
    let addAgeRangeItems = document.getElementsByClassName('add-gender-items age');
    let currentAge = '<?= $this->subject()->gg_age_range; ?>';
    for(var i = 0; i < addAgeRangeItems.length; i++) {

        let age = addAgeRangeItems[i].getElement('a').innerText;
        if(age == '65+') { age = '65'; } else if (age == 'Under 18') { age = '18'; }

        if(age == currentAge) {
            addAgeRangeItems[i].classList.add('selected-age');
            document.getElementById('profile_age_range').value = type;
            document.getElementById('<?= $this->name; ?>').value = type;
        }
    }
});

</script>