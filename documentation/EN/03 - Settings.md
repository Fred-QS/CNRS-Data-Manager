[Back to summary](/documentation/EN/01%20-%20Summary.md)

# Settings

> *Preparation of all the parameters necessary for the proper functioning of the extension.*

---

In this Parameters section, we will be able to approach the entry as well as the verification then validation of the URL of the ANN SOAP API, which will allow us to be able to retrieve the data necessary for the plugin to be able to function.

![Entered SOAP API URL](/documentation/attachments/illustration-01.png?raw=true)

When you have entered the endpoint URL, the extension will first check if the return code is indeed a 200 code, check that we are indeed reading XML, check the content and its structure and finally , if everything has been validated, allow the extension to unlock its functionalities.

![List of tabs](/documentation/attachments/illustration-02.png?raw=true)

The URL check icon will change from a red cross to a green check.

![Valid URL](/documentation/attachments/illustration-15.png?raw=true)

Once the URL is validated, the extension will unlock its functionalities. Several options and parameters will then appear.

To start, you can choose how the extension will display agents in the different team, department, and platform pages. 2 options are available:
- the widget option which will make a block appear where you have placed the corresponding shortcode in an entity's page.
- the page option which will make a button appear on the entity page which will link to the corresponding member page, thus displaying the agents who belong to the corresponding entity.

![Members page](/documentation/attachments/illustration-23.png?raw=true)
![Membership card](/documentation/attachments/illustration-24.png?raw=true)

If the page display is selected, the shortcode of the button to copy and paste into the page of the corresponding entity will be displayed as well as the shortcodes corresponding to the dynamic titles of the member pages.

![Display settings](/documentation/attachments/illustration-16.png?raw=true)
![View members button](/documentation/attachments/illustration-22.png?raw=true)

The following shortcodes correspond to the agent directory, i.e. a shortcode to copy and paste into a directory page and which will list all the agents with filters to be able to find them.

![Directory](/documentation/attachments/illustration-20.png?raw=true)

There will also be a 3D map builder shortcode this shortcode will allow you to display the 3D map in a page.

And finally the projects of a team, a shortcode which must be copied and in each of the team pages in order to be able to highlight the projects of this one.

![Projects](/documentation/attachments/illustration-27.png?raw=true)
![Display settings](/documentation/attachments/illustration-17.png?raw=true)

Then comes the part which will allow you to use filters as well as pagination.
Shortcodes are offered to you with a list of filtering modules and the possibility of silent pagination.
You can also use the kit of custom pages that are category.php, archive.php and project.php provided by the extension. This allows you to customize them directly with PHP code.

![List of projects](/documentation/attachments/illustration-28.png?raw=true)
![List of categories](/documentation/attachments/illustration-21.png?raw=true)

You can also implement the filtering and/or pagination system manually using the shortcodes in this part.

![Filters and pagination](/documentation/attachments/illustration-18.png?raw=true)

Finally, to allow the extension to link the data from the XML file received by the API to the corresponding categories, it will be necessary to assign the WordPress categories to the teams, services and platforms.
Radio buttons will allow you to apply a filter to the shortcode (cutting the list of agents by filter) proposed by entity, a default list or grid view, as well as the possibility of displaying the view selector.

![Category assignment](/documentation/attachments/illustration-25.png?raw=true)
![Category assignment](/documentation/attachments/illustration-19.png?raw=true)

If you check no for the view selector display, the default view will be the online view for agents.

![Grid view](/documentation/attachments/illustration-26.png?raw=true)

---

- [< Installation](/documentation/EN/02%20-%20Installation.md)

- [> Dashboard](/documentation/EN/04%20-%20Dashboard.md)