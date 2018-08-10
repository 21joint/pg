# Parental Guidance #
<br><br>
### Whats Included ###


- Bootstrap (latest) [official docs](https://getbootstrap.com)
- SCSS stylesheets [official docs](https://scsslang.com) 
- Webpack (latest) [official docs](https://webpack.js.org)

<br>

### Quick start ###


At first please make sure you have Node.js stable installed on your local machine. <br>

You can check *`NodeJS`* presence by running `node -v` in your terminal
                   which can be found on their [official website](https://nodejs.org). <br>

1. Have your `apache` server running using *XAMPP* or *MAMP(OSX)*.

2. In the terminal run `cd /path/to/the/project_root` and run `npm install`
    
    - After the process is successfully complete, without any errors, you can find out that a **`node_modules`** folder is created, 
    which is going to contain all the: 
     
      - `dependencies`*`(code which is going to get into production bundles)`*
      - `devDependencies`*`(development only stuff)`* 
  
3. `npm run dev` will run a development server on port `2121`

    *<p><small>note: our development server is a proxy server, and it has built in middleware, which is needed to handle the API calls from our local `http` server to `https`. You can get all the server configuration variables in `webpack.dev.config.js`</small></p>*
    
    -   once we have the `development` server running, all the bundled js/css files are available using url:  <br>

            '/{scripts|styles}/{module_title.bundle.{js | css}}'

4. `npm run build` is building `{ js | css }` bundles


<br>

### Moving Around ###


<div>
 
 <span>The **[socialengine](socialengine.org)** platform has its frontend layer based in the *active* theme folder.Accordingly we have ./application/themes"**/current_theme**) folder, which has the following structure:</span>

</div>

<br>  

- **/modules**
- **/components**
- **/services**
- */scss* <small>(common styles)</small>
- */images*
- */fonts*

<br>



<div>

##### Modules
 
```markdown
theme_directory
  └──  modules
          └── [module_title]/[module_title].module.js       
```

<small>

[\*] all module files need to have extensions like: /[module_title]/[module_title]***.module.js***
</small>

- *<small><i>creating new modules</i>*

  Another thing, that needs to be mentioned about the **modules** - <br> is that: everytime we create a new **modules**, we have to add it into the `entries` <br></small>

##### Components <br>

```markdown
theme_directory
  └── components
        └── [component_title]/[component_title].js
        └── [component_title]/[component_stylesheet].scss
```

##### Services <br>

<p>

- `services` should used/created to interact with the `API` layer
- Right now we have only one service - `api.service.js`. <br>
- we may need to create some new `services`, if we find out we need to separate some logic related to the API calls 

```markdown
theme_directory
  └── services
          └── [service_title]/[service_title].js
```

</div>

<br><br><br>

### Advantages <br>


  - **development**

    - better structured code, where even *`filename`* is self explanatory
      - **[module]** is able to import any assets like:
      
        -  css/scss/style
        -   images
        -   fonts
        -   JS | ES5 | ES6
         -  html (though we don't need it right now, as a module)
         
    - all the vendor code always up to date with the best JS package manager - [`npm`](https://www.npmjs.com/) 
    - any `package` can be installed doing simply `npm install package-name` and can be updated via `npm update` ([`find packages`](https://www.npmjs.com/))
    - with the latest vendor codes (plugins, libraries like `jquery`, `bootstrap`)
    - most of our *javascript* will be out of `php` (`.tpl` files)
    - everything related to a <small> ***[ module ]*** </small> is imported in its <small> ***[ *.module.js ]*** </small>
    - ability to import only some parts of vendor code, for every particular `module` / `widget`
    - ability to use modern javascript - `ES6` techniques [template literals](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Template_literals) 
    - all the  `vendor` code coming from [`npm`](https://www.npmjs.com/) (e.g. `jquery`, `bootstrap`)
    - all `API` calls organized in particular `services`
    - source code, which is going to be more than scalable
    - ability to easily start using any `js` framework / library (angular 6, react, vuejs), in case we need it in future
                                                                                                                        
  - **production** <small>(*)</small>
   
    - single (minified) `{module_title}.bundle.js` file per widget
    - single (minified) `{module_title}.bundle.css` file per widget
    - cross browser `js` code after bablifying 
       
    
                                                                                                                        