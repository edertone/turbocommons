# How to make the library available to the public:

1 - Make sure all tests pass

2 - Commit and push all the new version changes to repository.

3 - Review git changelog to decide the new version value based on the GIT changes: minor, major, ...

4 - Make sure the git tag is updated with the new project version we want to publish
    (First in remote GIT repo and then in our Local by performing a fetch)

5 - Generate a release build executing tests (tb -crt)

6 - Create a folder on your desktop and add all files from dist/NNN/

7 - Copy the package.json file from this folder to each of the dist/NNN/ created folders

8 - Add the readme.md file if exists to each of the dist/NNN/ folders

9 - Update the version number on the package.json file

10 - Update the project name on the package.json file:
    turbocommons-es5
    turbocommons-es6
    turbocommons-ts

11 - Open a command line inside each package.json folder and run:
    npm publish

12 - Verify that new versions appear for all the packages at www.npmjs.com/~edertone

13 - Get the downloadable zip files for each target and update the files inside with the new versions
    - docs, readme, compiled code, etc..

14 - Upload the new zip versions to turboframework website for direct download
    - review that zip download work as expected

15 - Upload the new generated docs to the turboframework website
    - review that links to docs still work
