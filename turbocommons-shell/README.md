# Shell script common utilities library

This project contains a collection of commonly used shell functions and utilities that can be easily imported and executed in our own scripts.

## Folder structure

The utilities are classified into different folders depending on the OS or distribution they can be executed.

## How to use in ubuntu distributions

To import a set of utilities into your own script, simply put the following at the beginning:

```
# Load turbocommons common tools from github
source <(curl -fsSL "https://raw.githubusercontent.com/edertone/turbocommons/<sha>/turbocommons-shell/ubuntu/script-common-tools.sh")

```

Were <sha> is the hash of the commit you want to import. All the methods will be directly available for your script.
