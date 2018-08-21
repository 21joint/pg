<!--FOOTER-->
<footer class="prg-footer">
  <div class="bg-white border-top prg-footer--top">
    <div class="container">
      <div class="top-holder row">
        <div
          class="section-one col-12 col-md-3 d-flex justify-content-md-start justify-content-center mb-4 mb-md-0">
          <a href="<?= $this->baseUrl(); ?>">
            <svg width="110px" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 216.315 60">
              <use href="#ggLogo"></use>
            </svg>
          </a>
        </div> <!-- end of section-one -->

        <div class="section-two col-6 col-md-3 mb-4 mb-md-0">
          <div class="title-holder mb-4">
            <h6 class="text-uppercase border-white font-weight-bold m-0 p-0">
              <?= $this->translate('Company'); ?>
            </h6>
          </div>

          <div class="description-holder">
            <ul class="list-unstyled m-0">
              <li>
                <a href="http://www.guidanceguide.com" target="_blank">
                  <?= $this->translate('Guidance Guide'); ?>
                </a>
              </li>
              <li>
                <a href="http://www.guidanceguide.com/about.php"
                   target="_blank">
                  <?= $this->translate('About Us'); ?>
                </a>
              </li>
              <li>
                <a href="http://www.guidanceguide.com/contact.php">
                  <?= $this->translate('Contact Us'); ?>
                </a>
              </li>
            </ul>
          </div>

        </div> <!-- end of section-two -->

        <div class="section-three col-6 col-md-3 mb-4 mb-md-0">

          <div class="title-holder mb-4">
            <h6 class="text-uppercase border-white font-weight-bold m-0 p-0">
              <?= $this->translate('Our Communitties'); ?>
            </h6>
          </div>

          <div class="description-holder">
            <ul class="list-unstyled">
              <li>
                <a href="javascript:void(0)">
                  <?= $this->translate('Parental Guidance'); ?>
                </a>
                <ul>
                  <li>
                    <a href="javascript:void(0)">
                      <?= $this->translate('Reviews'); ?>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)">
                      <?= $this->translate('Guides'); ?>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)">
                      <?= $this->translate('Struggles & Theories'); ?>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div> <!-- end of description holder -->

        </div> <!-- end of section-three -->

        <div class="section-four col-md-3">

          <div class="title-holder mb-3">
            <h6
              class="text-uppercase border-white font-weight-bold m-0 p-0 d-none d-sm-block">
              <?= $this->translate('Connect with Us'); ?>
            </h6>
          </div>

          <div class="prg-footer--social">

            <ul class="list-inline text-center text-md-left m-0 list-unstyled">
              <li class="list-inline-item align-middle">
                <a class="d-flex align-items-center justify-content-center"
                   href="javascript:void(0);">
                  <svg aria-hidden="true" data-prefix="fab"
                       data-icon="instagram" role="img"
                       xmlns="http://www.w3.org/2000/svg"
                       viewBox="0 0 448 512">
                    <path fill="currentColor"
                          d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
                          class=""></path>
                  </svg>
                </a>
              </li> <!-- instagram -->
              <li class="list-inline-item align-middle">
                <a class="d-flex align-items-center justify-content-center"
                   href="javascript:void(0);">
                  <svg aria-hidden="true" data-prefix="fab"
                       data-icon="facebook-f" role="img"
                       xmlns="http://www.w3.org/2000/svg"
                       viewBox="0 0 264 512">
                    <path fill="currentColor"
                          d="M76.7 512V283H0v-91h76.7v-71.7C76.7 42.4 124.3 0 193.8 0c33.3 0 61.9 2.5 70.2 3.6V85h-48.2c-37.8 0-45.1 18-45.1 44.3V192H256l-11.7 91h-73.6v229"
                          class=""></path>
                  </svg>
                </a>
              </li> <!-- facebook -->
              <li class="list-inline-item align-middle">
                <a class="d-flex align-items-center justify-content-center"
                   href="javascript:void(0);">
                  <svg aria-hidden="true" data-prefix="fab" data-icon="twitter"
                       role="img" xmlns="http://www.w3.org/2000/svg"
                       viewBox="0 0 512 512">
                    <path fill="currentColor"
                          d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"
                          class=""></path>
                  </svg>
                </a>
              </li> <!-- twiiter -->
              <li class="list-inline-item align-middle">
                <a class="d-flex align-items-center justify-content-center"
                   href="javascript:void(0);">
                  <svg aria-hidden="true" data-prefix="fab"
                       data-icon="pinterest-p"
                       role="img" xmlns="http://www.w3.org/2000/svg"
                       viewBox="0 0 384 512">
                    <path fill="currentColor"
                          d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"
                          class=""></path>
                  </svg>
                </a>
              </li><!-- pinterest -->
            </ul>
          </div>

        </div> <!-- end of section-four -->

      </div> <!-- end of top-holder -->
    </div> <!-- end of container -->

  </div> <!-- end of footer-top -->
  <div class="bg-white border-top prg-footer--bottom">
    <div class="container">
      <div class="bottom-holder row justify-content-between">
        <div
          class="col-auto d-flex text-left small text-muted font-weight-light">
          <?= $this->translate('Copyright &copy 2018'); ?>
        </div>
        <div
          class="col-auto text-right d-flex justify-content-start justify-content-sm-end">
          <ul class="list-inline small text-muted font-weight-light m-0">
            <li class="list-inline-item align-middle">
              <a href="<?= $this->baseUrl() . '/help/privacy' ?>">
                <?= $this->translate('Privacy Policy'); ?>
              </a>
            </li>
            <li class="list-inline-item align-middle">
              <a href="<?= $this->baseUrl() . '/help/terms' ?>">
                <?= $this->translate('Terms Of Use'); ?>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div> <!-- end of container -->
  </div> <!-- end of footer-bottom-->
</footer>
<!--FOOTER ends -->
<div style="position: absolute; width: 0; height: 0;top: -9999px;">
  <svg class="align-middle"
       xmlns="http://www.w3.org/2000/svg">
    <defs>
      <style> .star-cls--1 {
          fill: url(#linear-gradient);
        }

        .star-cls--2 {
          fill: url(#linear-gradient-2);
        }

        .star-cls--3 {
          fill: url(#linear-gradient-3);
        }

        .ggLogo-a {
          fill: #fed713;
        }

        .ggLogo-b {
          fill: #8f8279;
        }

        .ggLogo-c {
          fill: url(#a);
        }

        .ggLogo-d {
          fill: url(#b);
        }</style>
      <linearGradient id="linear-gradient" x1="-0.222" y1="0.952" x2="0.971"
                      y2="-0.34" gradientUnits="objectBoundingBox">
        <stop offset="0" stop-color="#ce8f2a"></stop>
        <stop offset="0.17" stop-color="#cf9330"></stop>
        <stop offset="0.38" stop-color="#d39d3f"></stop>
        <stop offset="0.62" stop-color="#d8ae59"></stop>
        <stop offset="0.67" stop-color="#dab360"></stop>
        <stop offset="0.92" stop-color="#edc64f"></stop>
        <stop offset="1" stop-color="#edc64f"></stop>
      </linearGradient>
      <linearGradient id="linear-gradient-2" x1="1.543" y1="1.119" x2="0.162"
                      y2="-0.102" gradientUnits="objectBoundingBox">
        <stop offset="0" stop-color="#ce8f2a"></stop>
        <stop offset="0.17" stop-color="#a28f70"></stop>
        <stop offset="0.38" stop-color="#d39d3f"></stop>
        <stop offset="0.62" stop-color="#d8ae59"></stop>
        <stop offset="0.67" stop-color="#dab360"></stop>
        <stop offset="0.92" stop-color="#edc64f"></stop>
        <stop offset="1" stop-color="#edc64f"></stop>
      </linearGradient>
      <linearGradient id="linear-gradient-3" x1="1.616" y1="1.474" x2="0.258"
                      y2="-0.026"
                      xlink:href="#linear-gradient"></linearGradient>
      <linearGradient id="a" y1="0.5" x2="1" y2="0.5"
                      gradientUnits="objectBoundingBox">
        <stop offset="0" stop-color="#bbb6b0"/>
        <stop offset="0.05" stop-color="#bfbab3"/>
        <stop offset="0.27" stop-color="#cdc8c0"/>
        <stop offset="0.54" stop-color="#d5d0c8"/>
        <stop offset="1" stop-color="#d8d3ca"/>
      </linearGradient>
      <linearGradient id="b" y1="0.5" x2="1" y2="0.5"
                      gradientUnits="objectBoundingBox">
        <stop offset="0" stop-color="#d8d3ca"/>
        <stop offset="0.46" stop-color="#d5d0c8"/>
        <stop offset="0.73" stop-color="#cdc8c0"/>
        <stop offset="0.95" stop-color="#bfbab3"/>
        <stop offset="1" stop-color="#bbb6b0"/>
      </linearGradient>
    </defs>
    <symbol id="ggLogo">
      <g transform="translate(-2.361 -2.293)">
        <path class="ggLogo-a"
              d="M172.122,35.136a10.167,10.167,0,0,1-6.593,2.43,10,10,0,0,1-2.712-.367,10.115,10.115,0,0,1-2.435-1.036,10.551,10.551,0,0,1-2.067-1.6,9.959,9.959,0,0,1-1.6-2.067,10.392,10.392,0,0,1-1.027-2.449,10.067,10.067,0,0,1-.367-2.717,9.916,9.916,0,0,1,.367-2.7,10.462,10.462,0,0,1,1.027-2.435,9.959,9.959,0,0,1,1.6-2.067,10.171,10.171,0,0,1,13.807-.565L170.055,23a6.644,6.644,0,0,0-2.067-1.413,6.183,6.183,0,0,0-4.907,0,6.485,6.485,0,0,0-1.992,1.347,6.339,6.339,0,0,0-1.352,1.987,6.023,6.023,0,0,0-.494,2.435,6.122,6.122,0,0,0,.5,2.425,6.376,6.376,0,0,0,3.344,3.353,6.023,6.023,0,0,0,2.444.494,5.839,5.839,0,0,0,1.45-.179,6.635,6.635,0,0,0,1.337-.494V27.309h3.805Z"
              transform="translate(-80.928 -7.852)"/>
        <path class="ggLogo-a"
              d="M215.607,29.471a7.71,7.71,0,0,1-.622,3.085,7.912,7.912,0,0,1-1.7,2.51,8.053,8.053,0,0,1-2.519,1.691,7.911,7.911,0,0,1-10.37-4.2,7.707,7.707,0,0,1-.622-3.084V17.59h3.946V29.471a3.815,3.815,0,0,0,.311,1.53,3.961,3.961,0,0,0,7.294,0,3.815,3.815,0,0,0,.311-1.53V17.59h3.96Z"
              transform="translate(-104.45 -8.093)"/>
        <path class="ggLogo-a" d="M247.4,37.389h-3.96V17.61h3.96Z"
              transform="translate(-127.55 -8.104)"/>
        <path class="ggLogo-a"
              d="M278.256,27.495a9.757,9.757,0,0,1-1.352,4.987,10.187,10.187,0,0,1-1.545,2,9.819,9.819,0,0,1-6.993,2.906H262.4V17.61h5.962a9.743,9.743,0,0,1,2.628.353,9.842,9.842,0,0,1,4.365,2.552A10.194,10.194,0,0,1,276.9,22.5a9.756,9.756,0,0,1,1.352,4.987Zm-3.96,0a5.779,5.779,0,0,0-.471-2.3,5.962,5.962,0,0,0-1.271-1.884,6.038,6.038,0,0,0-1.865-1.276,5.755,5.755,0,0,0-2.312-.471H266.4V33.428h1.973a5.82,5.82,0,0,0,2.312-.471,5.906,5.906,0,0,0,3.16-3.16A5.825,5.825,0,0,0,274.3,27.495Z"
              transform="translate(-137.582 -8.104)"/>
        <path class="ggLogo-a"
              d="M319.747,37.378h-3.96v-3.96h-7.921v3.96H303.92V25.511a7.713,7.713,0,0,1,.622-3.085,8.057,8.057,0,0,1,1.691-2.519,7.935,7.935,0,0,1,12.917,2.543,7.712,7.712,0,0,1,.622,3.085Zm-3.96-7.907v-3.96a3.86,3.86,0,0,0-.311-1.54,3.948,3.948,0,0,0-7.28,0,3.863,3.863,0,0,0-.311,1.54v3.96Z"
              transform="translate(-159.549 -8.094)"/>
        <path class="ggLogo-a"
              d="M363.413,37.378h-4.238l-7.624-13v13h-3.96V17.6h4.238l7.629,13.04V17.59h3.96Z"
              transform="translate(-182.654 -8.093)"/>
        <path class="ggLogo-a"
              d="M406.319,35.036a10.238,10.238,0,1,1-9.348-17.669A10,10,0,0,1,399.684,17a10.214,10.214,0,0,1,3.546.626,9.831,9.831,0,0,1,3.089,1.827l-2.1,3.447a5.93,5.93,0,0,0-2.058-1.441,6.292,6.292,0,0,0-2.482-.5,6.122,6.122,0,0,0-2.444.5,6.46,6.46,0,0,0-2,1.347,6.3,6.3,0,0,0-1.352,2,6.121,6.121,0,0,0-.494,2.449,6.019,6.019,0,0,0,.494,2.435,6.376,6.376,0,0,0,3.353,3.344,6.122,6.122,0,0,0,2.453.494,6.287,6.287,0,0,0,2.472-.518,5.926,5.926,0,0,0,2.058-1.413Z"
              transform="translate(-204.798 -7.781)"/>
        <path class="ggLogo-a"
              d="M446.472,37.389H432.91V17.61h13.562v3.951h-9.607v3.96h6.5v3.96h-6.5v3.946h9.6Z"
              transform="translate(-227.796 -8.104)"/>
        <path class="ggLogo-b"
              d="M172.122,92.936a10.167,10.167,0,0,1-6.593,2.43A10,10,0,0,1,162.817,95a10.115,10.115,0,0,1-2.435-1.036,10.552,10.552,0,0,1-2.067-1.6,9.958,9.958,0,0,1-1.6-2.067,10.393,10.393,0,0,1-1.027-2.449,10.067,10.067,0,0,1-.367-2.717,9.915,9.915,0,0,1,.367-2.7,10.462,10.462,0,0,1,1.027-2.435,9.958,9.958,0,0,1,1.6-2.067,10.171,10.171,0,0,1,13.807-.565L170.055,80.8a6.644,6.644,0,0,0-2.067-1.413,6.183,6.183,0,0,0-4.907,0,6.485,6.485,0,0,0-1.992,1.347,6.339,6.339,0,0,0-1.352,1.987,6.023,6.023,0,0,0-.494,2.435,6.122,6.122,0,0,0,.494,2.453,6.376,6.376,0,0,0,3.344,3.353,6.023,6.023,0,0,0,2.449.48,5.84,5.84,0,0,0,1.45-.179,6.635,6.635,0,0,0,1.337-.494V85.123h3.805Z"
              transform="translate(-80.928 -38.433)"/>
        <path class="ggLogo-b"
              d="M215.607,87.277a7.71,7.71,0,0,1-.622,3.085,7.912,7.912,0,0,1-1.7,2.51,8.053,8.053,0,0,1-2.519,1.691,7.911,7.911,0,0,1-10.37-4.2,7.707,7.707,0,0,1-.622-3.085V75.4h3.946V87.277a3.815,3.815,0,0,0,.311,1.53,3.961,3.961,0,0,0,7.294,0,3.815,3.815,0,0,0,.311-1.53V75.4h3.96Z"
              transform="translate(-104.45 -38.68)"/>
        <path class="ggLogo-b" d="M247.4,95.189h-3.96V75.41h3.96Z"
              transform="translate(-127.55 -38.685)"/>
        <path class="ggLogo-b"
              d="M278.256,85.3a9.757,9.757,0,0,1-1.356,4.992,10.184,10.184,0,0,1-1.545,2,9.819,9.819,0,0,1-6.993,2.906H262.4V75.42h5.962a9.743,9.743,0,0,1,2.628.353,9.842,9.842,0,0,1,4.365,2.552,10.193,10.193,0,0,1,1.545,2,9.756,9.756,0,0,1,1.352,4.987Zm-3.96,0a5.779,5.779,0,0,0-.471-2.3,5.962,5.962,0,0,0-1.271-1.884,6.037,6.037,0,0,0-1.884-1.276,5.755,5.755,0,0,0-2.312-.471h-1.973V91.233h1.973a5.821,5.821,0,0,0,2.312-.471,5.905,5.905,0,0,0,3.16-3.16A5.826,5.826,0,0,0,274.3,85.3Z"
              transform="translate(-137.582 -38.69)"/>
        <path class="ggLogo-b"
              d="M317.952,95.189H304.39V75.41h13.562v3.951h-9.6v3.96h6.5v3.96h-6.5v3.946h9.6Z"
              transform="translate(-159.798 -38.685)"/>
        <path class="ggLogo-c"
              d="M107.2,26.808l-11.3,14.9L86.2,28.772l18.809-4.238C107.768,23.837,108.569,24.943,107.2,26.808Z"
              transform="translate(-44.358 -11.656)"/>
        <path class="ggLogo-d"
              d="M28.506,40.68,3.755,46.854c-1.578.358-1.926,2.03-.471,2.689l24.139,7.064Z"
              transform="translate(0 -20.31)"/>
        <path class="ggLogo-a"
              d="M51.227,60.031,54.415,3.747c0-1.592,1.644-1.964,2.628-.678L90.737,45.075c1.248,1.474.9,4.083-2.571,2.717l-20.32-7.78L55.154,61.02C53.868,63.238,51.118,62.334,51.227,60.031Z"
              transform="translate(-25.852 0)"/>
      </g>
    </symbol>
    <symbol id="prgLogo" viewBox="0 0 480.81 171.45">
      <path
        d="M897.82,523.2h-8.69V479.79h17.38a17,17,0,0,1,4.6.62,17.29,17.29,0,0,1,4.13,1.76,17.63,17.63,0,0,1,3.51,2.72,17.46,17.46,0,0,1,4.48,7.67,17.35,17.35,0,0,1-.74,11.34,17.43,17.43,0,0,1-9.25,9.25,16.85,16.85,0,0,1-6.74,1.36h-8.69Zm0-34.72v17.38h8.69a8.37,8.37,0,0,0,3.36-.68,8.71,8.71,0,0,0,4.62-4.63,8.7,8.7,0,0,0,0-6.75,8.71,8.71,0,0,0-4.62-4.63,8.37,8.37,0,0,0-3.36-.68Z"
        transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path
        d="M963.8,523.2h-8.69v-8.69H937.74v8.69h-8.66v-26a16.93,16.93,0,0,1,1.36-6.77,17.67,17.67,0,0,1,3.71-5.52,17.36,17.36,0,0,1,5.51-3.72,17.48,17.48,0,0,1,13.53,0,17.42,17.42,0,0,1,9.25,9.25,16.93,16.93,0,0,1,1.36,6.77Zm-8.69-17.35v-8.69a8.48,8.48,0,0,0-.68-3.38,8.67,8.67,0,0,0-4.63-4.63,8.7,8.7,0,0,0-6.75,0,8.67,8.67,0,0,0-4.63,4.63,8.48,8.48,0,0,0-.68,3.38v8.69Z"
        transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path
        d="M980.65,523.2H972V479.79h17.38a17,17,0,0,1,4.6.62,17.28,17.28,0,0,1,4.13,1.76,17.57,17.57,0,0,1,3.51,2.72,17.46,17.46,0,0,1,4.48,7.67,17.3,17.3,0,0,1,.06,9,17.86,17.86,0,0,1-1.6,4,17,17,0,0,1-2.56,3.51,17.34,17.34,0,0,1-3.39,2.79l4.81,11.35h-9.23l-3.78-8.75-9.72.06Zm0-34.72v17.38h8.69a8.37,8.37,0,0,0,3.36-.68,8.72,8.72,0,0,0,4.62-4.63,8.71,8.71,0,0,0,0-6.75,8.72,8.72,0,0,0-4.62-4.63,8.37,8.37,0,0,0-3.36-.68Z"
        transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path
        d="M1042.75,523.2H1013V479.79h29.76v8.69h-21.07v8.69h14.26v8.69h-14.26v8.66h21.07Z"
        transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path
        d="M1084.18,523.2h-9.29l-16.74-28.58V523.2h-8.69V479.79h9.29l16.74,28.61V479.79h8.69Z"
        transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path d="M1111.08,523.2h-8.66V488.48h-13v-8.69h34.72v8.69h-13Z"
            transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path
        d="M1162.26,523.2h-8.69v-8.69h-17.38v8.69h-8.66v-26a16.93,16.93,0,0,1,1.36-6.77,17.67,17.67,0,0,1,3.71-5.52,17.35,17.35,0,0,1,5.51-3.72,17.48,17.48,0,0,1,13.53,0,17.42,17.42,0,0,1,9.25,9.25,16.93,16.93,0,0,1,1.36,6.77Zm-8.69-17.35v-8.69a8.5,8.5,0,0,0-.68-3.38,8.67,8.67,0,0,0-4.63-4.63,8.7,8.7,0,0,0-6.75,0,8.67,8.67,0,0,0-4.63,4.63,8.5,8.5,0,0,0-.68,3.38v8.69Z"
        transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path d="M1200.81,523.2h-30.39V479.79h8.69v34.72h21.71Z"
            transform="translate(-720 -453.29)" fill="#5cc7ce"></path>
      <path
        d="M923.68,579.63a22.32,22.32,0,0,1-14.5,5.33,22,22,0,0,1-5.95-.8,22.23,22.23,0,0,1-5.34-2.27,23.09,23.09,0,0,1-4.54-3.51,21.81,21.81,0,0,1-3.51-4.54,22.8,22.8,0,0,1-2.26-5.37,22.08,22.08,0,0,1-.8-6,21.75,21.75,0,0,1,.8-5.93,23,23,0,0,1,2.26-5.34,21.81,21.81,0,0,1,3.51-4.54,22.5,22.5,0,0,1,9.88-5.75,21.92,21.92,0,0,1,5.95-.8,22.3,22.3,0,0,1,14.5,5.33L919.13,553a14.56,14.56,0,0,0-4.54-3.13,13.57,13.57,0,0,0-10.76,0,14.21,14.21,0,0,0-4.37,3,13.89,13.89,0,0,0-3,4.36,13.21,13.21,0,0,0-1.09,5.34,13.39,13.39,0,0,0,1.09,5.39,14,14,0,0,0,7.34,7.36,13.21,13.21,0,0,0,5.34,1.09,12.8,12.8,0,0,0,3.18-.39,14.55,14.55,0,0,0,2.94-1.09V562.49h8.39Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M967,566.67a16.93,16.93,0,0,1-1.36,6.77,17.37,17.37,0,0,1-3.72,5.51,17.68,17.68,0,0,1-5.53,3.71,17.48,17.48,0,0,1-13.53,0,17.31,17.31,0,0,1-9.22-9.22,16.92,16.92,0,0,1-1.36-6.77V540.61H941v26.07a8.37,8.37,0,0,0,.68,3.36,8.72,8.72,0,0,0,4.63,4.62,8.71,8.71,0,0,0,6.75,0,8.72,8.72,0,0,0,4.63-4.62,8.37,8.37,0,0,0,.68-3.36V540.61H967Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path d="M985.67,584H977V540.61h8.69Z"
            transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M1030.92,562.31a21.41,21.41,0,0,1-3,10.94,22.41,22.41,0,0,1-3.39,4.39,21.54,21.54,0,0,1-15.35,6.37H996.14V540.61h13.08a21.41,21.41,0,0,1,5.77.77,21.59,21.59,0,0,1,9.58,5.6,22.41,22.41,0,0,1,3.39,4.39,21.41,21.41,0,0,1,3,10.94Zm-8.69,0a12.66,12.66,0,0,0-1-5.06,13.08,13.08,0,0,0-2.79-4.13,13.26,13.26,0,0,0-4.15-2.8,12.64,12.64,0,0,0-5.07-1h-4.33v26h4.33a12.78,12.78,0,0,0,5.07-1,13,13,0,0,0,6.93-6.93A12.77,12.77,0,0,0,1022.23,562.31Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M1073.33,584h-8.69v-8.69h-17.38V584h-8.66V558a16.92,16.92,0,0,1,1.36-6.77,17.67,17.67,0,0,1,3.71-5.53,17.35,17.35,0,0,1,5.51-3.72,17.48,17.48,0,0,1,13.53,0,17.42,17.42,0,0,1,9.25,9.25,16.92,16.92,0,0,1,1.36,6.77Zm-8.69-17.35V558a8.49,8.49,0,0,0-.68-3.37,8.68,8.68,0,0,0-4.63-4.63,8.71,8.71,0,0,0-6.75,0,8.68,8.68,0,0,0-4.63,4.63,8.49,8.49,0,0,0-.68,3.38v8.69Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M1118,584h-9.29L1092,555.44V584h-8.69V540.61h9.29l16.74,28.61V540.61H1118Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M1163.15,579.57a22.39,22.39,0,0,1-20.51,4.59,22.62,22.62,0,0,1-15.71-15.71,22.52,22.52,0,0,1,0-11.91,22.48,22.48,0,0,1,5.8-9.93,22.48,22.48,0,0,1,9.92-5.8,21.91,21.91,0,0,1,5.95-.8,22.42,22.42,0,0,1,7.78,1.38,21.59,21.59,0,0,1,6.78,4l-4.6,7.57a13,13,0,0,0-4.51-3.16,13.82,13.82,0,0,0-5.45-1.1,13.39,13.39,0,0,0-5.39,1.09,14.15,14.15,0,0,0-4.39,3,13.8,13.8,0,0,0-3,4.39,13.43,13.43,0,0,0-1.09,5.37,13.21,13.21,0,0,0,1.09,5.34,14,14,0,0,0,7.36,7.34,13.39,13.39,0,0,0,5.39,1.09,13.8,13.8,0,0,0,5.45-1.11,13,13,0,0,0,4.51-3.16Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M1200.35,584h-29.76V540.61h29.76v8.69h-21.07V558h14.26v8.69h-14.26v8.66h21.07Z"
        transform="translate(-720 -453.29)" fill="#90847a"></path>
      <path
        d="M852.24,597.25a18.41,18.41,0,1,1-35,11.51l0-.13a18.75,18.75,0,0,1,0-11.23,15.76,15.76,0,0,1,2.08-4.39,18.62,18.62,0,0,1,22.26-7.23l1.5,4.61a14.84,14.84,0,0,0-12.84-1.66,15.32,15.32,0,0,0-9.42,8.89,14.59,14.59,0,0,0-.27,10,15,15,0,0,0,28.59-9.29L833.58,550.6,829.44,549c.08.21.15.41.22.62L840.17,582a21.82,21.82,0,0,0-12.19.35,20.89,20.89,0,0,0-10,6.85l-10.54-32.43c-.12-.37-.21-.73-.29-1.1l-2.32,3.84,10.76,33.12a22.66,22.66,0,0,0-2,5l-11.12-34.23-2.28,3.77,13.82,42.53a21.74,21.74,0,0,0,41.39-13.34l-13.86-42.66-4-1.53Zm-6.43,2.09h0a11.65,11.65,0,1,1-14.65-7.54A11.71,11.71,0,0,1,845.81,599.34Z"
        transform="translate(-720 -453.29)" fill="#d8d2cb"></path>
      <path
        d="M869.26,555.42l-30.35-37.84-23.41-29.19-26.67-33.25c-2.35-3.07-6.27-2.18-6.27,1.62L780.27,497l-2.1,37-3.24,57.15c-.26,5.5,6.31,7.66,9.38,2.36l15.92-26.35,2.28-3.77,2.35-3.88,2.32-3.84,7.44-12.31L829.44,549l4.14,1.58,4,1.53,4,1.53,21.54,8.25C871.42,565.16,872.24,558.94,869.26,555.42Z"
        transform="translate(-720 -453.29)" fill="#5cc7cd"></path>
      <path d="M723.33,511.18c-3.77.85-4.6,4.85-1.12,6.42l56,16.38,2.1-37Z"
            transform="translate(-720 -453.29)" fill="#52b1b8"></path>
      <path
        d="M864.39,484c3.26-4.45,1.35-7.09-5.24-5.43l-43.66,9.84,23.41,29.19Z"
        transform="translate(-720 -453.29)" fill="#52b1b8"></path>
    </symbol>
    <symbol id="prgBlueStar" viewBox="0 0 180 180">
      <path
        d="M852.24,597.25a18.41,18.41,0,1,1-35,11.51l0-.13a18.75,18.75,0,0,1,0-11.23,15.76,15.76,0,0,1,2.08-4.39,18.62,18.62,0,0,1,22.26-7.23l1.5,4.61a14.84,14.84,0,0,0-12.84-1.66,15.32,15.32,0,0,0-9.42,8.89,14.59,14.59,0,0,0-.27,10,15,15,0,0,0,28.59-9.29L833.58,550.6,829.44,549c.08.21.15.41.22.62L840.17,582a21.82,21.82,0,0,0-12.19.35,20.89,20.89,0,0,0-10,6.85l-10.54-32.43c-.12-.37-.21-.73-.29-1.1l-2.32,3.84,10.76,33.12a22.66,22.66,0,0,0-2,5l-11.12-34.23-2.28,3.77,13.82,42.53a21.74,21.74,0,0,0,41.39-13.34l-13.86-42.66-4-1.53Zm-6.43,2.09h0a11.65,11.65,0,1,1-14.65-7.54A11.71,11.71,0,0,1,845.81,599.34Z"
        transform="translate(-720 -453.29)" fill="#d8d2cb"></path>
      <path
        d="M869.26,555.42l-30.35-37.84-23.41-29.19-26.67-33.25c-2.35-3.07-6.27-2.18-6.27,1.62L780.27,497l-2.1,37-3.24,57.15c-.26,5.5,6.31,7.66,9.38,2.36l15.92-26.35,2.28-3.77,2.35-3.88,2.32-3.84,7.44-12.31L829.44,549l4.14,1.58,4,1.53,4,1.53,21.54,8.25C871.42,565.16,872.24,558.94,869.26,555.42Z"
        transform="translate(-720 -453.29)" fill="#5cc7cd"></path>
      <path d="M723.33,511.18c-3.77.85-4.6,4.85-1.12,6.42l56,16.38,2.1-37Z"
            transform="translate(-720 -453.29)" fill="#52b1b8"></path>
      <path d="M723.33,511.18c-3.77.85-4.6,4.85-1.12,6.42l56,16.38,2.1-37Z"
            transform="translate(-720 -453.29)" fill="#52b1b8"></path>
      <path
        d="M864.39,484c3.26-4.45,1.35-7.09-5.24-5.43l-43.66,9.84,23.41,29.19Z"
        transform="translate(-720 -453.29)" fill="#52b1b8"></path>
    </symbol>
    <symbol id="prgSearch" viewBox="0 0 22 22">
      <path
        d="M20.855,19.231l-4.979-4.979a.487.487,0,0,0-.349-.144h-.541a8.529,8.529,0,1,0-.878.878v.541a.5.5,0,0,0,.144.349l4.979,4.979a.493.493,0,0,0,.7,0l.927-.927A.493.493,0,0,0,20.855,19.231ZM8.531,15.093a6.562,6.562,0,1,1,6.562-6.562A6.56,6.56,0,0,1,8.531,15.093Z"></path>
    </symbol>
    <symbol id="prgRateStar">
      <g transform="translate(-435 -1135)">
        <path class="star-cls--1"
              d="M173.963,249.27l-3.757,4.959L167,249.922l6.261-1.4C174.163,248.269,174.463,248.619,173.963,249.27Z"
              transform="translate(281.182 890.03)"></path>
        <path class="star-cls--2"
              d="M149.407,253.5l-8.265,2.054c-.5.1-.651.7-.15.9l8.065,2.354Z"
              transform="translate(294.316 887.504)"></path>
        <path class="star-cls--3"
              d="M156,260.741l1.052-18.734c0-.551.551-.651.9-.25l11.22,13.975c.4.5.3,1.352-.852.9l-6.762-2.6-4.208,7.013C156.9,261.843,155.948,261.543,156,260.741Z"
              transform="translate(286.674 893.486)"></path>
      </g>
    </symbol>
    <symbol id="prgBlueStarOnly" viewBox="0 0 14 13.327">
      <defs>
        <style>.starblue-cls-4 {
            fill: #5cc7cd
          }</style>
        <clipPath id="clip-path">
          <path id="Path_1203"
                data-name="Path 1203"
                d="M13.14 91.15L.237 94.263a.313.313 0 0 0-.015.606l5.156 1.5 5.646-1.527 2.438-3.2a.312.312 0 0 0-.322-.492z"
                transform="translate(.004 -91.141)"></path>
        </clipPath>
      </defs>
      <g id="android-chrome-512x512" transform="translate(-298 -272)">
        <g id="Group_2618" data-name="Group 2618"
           transform="translate(298.004 271.976)">
          <path id="Path_1201"
                data-name="Path 1201"
                d="M13.14 91.15L.237 94.263a.313.313 0 0 0-.015.606l5.156 1.5 5.646-1.527 2.438-3.2a.312.312 0 0 0-.322-.492z"
                transform="translate(0 -88.836)"
                fill="#53b2b8"></path>
          <g id="Group_2616" data-name="Group 2616"
             transform="translate(-.004 2.304)">
            <g id="Group_2615" data-name="Group 2615"
               clip-path="url(#clip-path)">
              <image id="Rectangle_4007"
                     data-name="Rectangle 4007"
                     width="8.609"
                     height="7.158"
                     transform="translate(3.902 -.441)"
                     xlink:href="data:image/png;base64,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"
                     opacity=".2"/>
              <g id="Group_2614" data-name="Group 2614"
                 transform="translate(5.406 1.053)">
                <path id="Path_1202"
                      data-name="Path 1202"
                      class="starblue-cls-4"
                      d="M221.6 135.843l-2.112-2.643-3.3.8-.188 3.354z"
                      transform="translate(-216 -133.2)"/>
              </g>
            </g>
          </g>
          <g id="Symbol_583_1" data-name="Symbol 583 – 1">
            <path id="Path_1204"
                  data-name="Path 1204"
                  class="starblue-cls-4"
                  d="M203.765.319l-.7 12.544a.464.464 0 0 0 .861.263l2.826-4.73 4.7 1.817a.387.387 0 0 0 .443-.6L204.323.141a.314.314 0 0 0-.558.178z"
                  transform="translate(-197.987)"/>
          </g>
        </g>
      </g>
    </symbol>
    </defs>
  </svg>
</div>

<script src="/scripts/header.bundle.js"></script>
<script src="/scripts/footer.bundle.js"></script>
