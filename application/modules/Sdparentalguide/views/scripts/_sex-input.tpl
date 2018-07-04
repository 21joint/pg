<div class="container p-0" id="family-information"> 
    <div class="row add-child-form add-another-child d-block mx-0 border-0">
        <div class="col-12 d-flex add-gender p-0 mb-4" id="add-gender"> 
            <div class="col-2 mr-1 p-0 text-center male small add-gender-items <?php if($this->subject()->gg_gender == 1): ?> selected<?php endif; ?>">
                <a href="javascript:void(0)" onclick="selectGender(1, this)" class="d-block p-2">
                    <div class="svg-holder w-100 text-center">
                        <svg id="male" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M20.44,3a.56.56,0,0,1,.56.56v3.7a.51.51,0,0,1-.35.52.47.47,0,0,1-.59-.14l-.8-.75-3.8,3.75a6.65,6.65,0,0,1,1,3.61,6.51,6.51,0,0,1-.91,3.38,6.88,6.88,0,0,1-2.46,2.46,6.69,6.69,0,0,1-6.75,0,6.88,6.88,0,0,1-2.46-2.46,6.69,6.69,0,0,1,0-6.75A6.88,6.88,0,0,1,6.38,8.41,6.51,6.51,0,0,1,9.75,7.5a6.65,6.65,0,0,1,3.61,1l3.75-3.8-.8-.8a.5.5,0,0,1-.09-.59A.51.51,0,0,1,16.73,3ZM9.75,18a3.76,3.76,0,0,0,3.75-3.75A3.76,3.76,0,0,0,9.75,10.5,3.76,3.76,0,0,0,6,14.25,3.76,3.76,0,0,0,9.75,18Z" fill="#333D40"/></svg>
                    </div>
                    <?php echo $this->translate('Male'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 p-0 text-center female small add-gender-items <?php if($this->subject()->gg_gender == 2): ?> selected<?php endif; ?>">
                <a href="javascript:void(0)" onclick="selectGender(2, this)" class="d-block p-2">
                    <div class="svg-holder w-100 text-center">
                        <svg id="female" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M0,0H24V24H0Z" fill="none"/><path d="M18.7,8.25a6.7,6.7,0,0,1-5.25,6.56v2.44h1.69a.56.56,0,0,1,.56.56v1.88a.56.56,0,0,1-.56.56H13.45v1.68a.56.56,0,0,1-.56.56H11a.56.56,0,0,1-.56-.56V20.25H8.76a.56.56,0,0,1-.56-.56V17.81a.56.56,0,0,1,.56-.56h1.69V14.81A6.7,6.7,0,0,1,5.2,8.25a6.52,6.52,0,0,1,.91-3.37A6.88,6.88,0,0,1,8.58,2.41a6.69,6.69,0,0,1,6.75,0,6.88,6.88,0,0,1,2.46,2.46A6.51,6.51,0,0,1,18.7,8.25Zm-10.5,0A3.61,3.61,0,0,0,9.3,10.9a3.74,3.74,0,0,0,5.3,0,3.61,3.61,0,0,0,1.1-2.65A3.61,3.61,0,0,0,14.6,5.6a3.74,3.74,0,0,0-5.3,0A3.61,3.61,0,0,0,8.2,8.25Z" fill="#333D40"/></svg>
                    </div>
                    <?php echo $this->translate('Female'); ?>
                </a>
            </div>
            <div class="col-2 mr-1 p-0 text-center d-flex align-items-center small not-to-answer add-gender-items <?php if($this->subject()->gg_gender == 3): ?> selected<?php endif; ?>">
                <a href="javascript:void(0)" onclick="selectGender(3, this)" class="d-block p-2">
                    <?php echo $this->translate('Prefer Not to Answer'); ?>
                </a>
            </div>
            <input type="hidden" name="profile_gender" id="profile_gender" value=""/>
        </div>
    </div> <!-- end of add-gender -->
</div>

<script>
function selectGender(type, e) {
    
    let genderField = document.getElementById('profile_gender').value = type;
    let addGenderItems = document.getElementsByClassName('add-gender-items');
    for(var i = 0; i < addGenderItems.length; i++) {
        addGenderItems[i].classList.remove('selected');
    }
    e.getParent().classList.add('selected');
}
</script>