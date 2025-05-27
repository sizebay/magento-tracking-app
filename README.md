# Sizebay Tracker (Magento)

## Getting Started

To get started with the Sizebay Tracker, follow these steps:

1. **Clone the repository:**

   ```bash
   git clone https://github.com/sizebay/magento-tracking-app.git
   cd magento-tracking-app
    ```
      <br>
2. **Ensure you have a Magento 2 framework installed:**
  The Sizebay Tracker module is designed to work with Magento 2. Make sure you have a Magento 2 framework installed and running. If you don't have Magento 2 installed, follow the [Magento Docker installation guide](https://github.com/markshust/docker-magento) developed by Mark Shust for a faster setup.

## Setup Environment

1. **Upload the module:**
  Copy the cloned repository to the modules directory of your Magento 2 following this steps:
    ```bash
    mkdir /path-to-your-magento/app/code/Sizebay
    cd $_
    mkdir ./SizebayTracker
    cd $_
    cp -r /path-to-your-cloned-respository/magento-tracking-app/* ./
    ```
    <br>
2. **Install the module:**
      Execute the following Magento commands on the console to install the module on development mode:
      ```bash
        bin/magento setup:upgrade
        bin/magento cache:clean 
        bin/magento cache:flush
      ```

    <br>
3. **Configure the module:**

    After installation, configure the module by providing the necessary information and settings in the module configuration page in the Magento 2 admin panel. For more details on how to do it access the [Shopping Tracker Documentation for Magento](https://docs.sizebay.com/shopping-tracker/platforms/magento).
    <br>


## Compile
### Compile New Version of the Project
To compile a new version of the project, follow these steps:

1. **Pull the latest changes:**

    ```bash
    git pull origin master
    ```
    <br>
2. **Update the module:**

   Execute the following Magento commands to ensure the module updates on the platform:

    ```bash
      bin/magento setup:upgrade
      bin/magento cache:clean 
      bin/magento cache:flush
    ```
    <br>


## Help
 - [Magento DevDocs](https://developer.adobe.com/commerce/docs/)
 - [Sizebay Shopping Tracker Docs](https://docs.sizebay.com/shopping-tracker/introduction)
 - [Magento Docker Guide](https://github.com/markshust/docker-magento)
 - [Magento Observers](https://developer.adobe.com/commerce/php/development/components/events-and-observers/)
