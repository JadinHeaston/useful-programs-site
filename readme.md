# Useful Programs Site
Over the years, I've accumulated several applications and software that I often need to have quick access to without internet access.  

In the past I've used [dirhtml](https://dirhtml.en.uptodown.com/windows) to generate a simple webpage that showed all of the files in their main group folder. However, generating a new page each time the files were modified was too much friction and ultimately never got done more than a handful of times.  

This project is dedicated towards eliminating that friction and providing additional ease-of-use features.  
I plan on eventually having this hosted on a LAN Web Server to be accessed anywhere internally. It just needs to be more functional than opening the folders themselves.  

# Table of Contents
- [Useful Programs Site](#useful-programs-site)
- [Table of Contents](#table-of-contents)
- [Usage](#usage)
- [Future Ideas](#future-ideas)

# Usage
This project is kept pretty simple. PHP was chosen as the backend to read the contents of a provided directory, which can be defined at the top of [index.php](index.php).

# Future Ideas
Current ideas being implemented are available in the [tasks.todo](tasks.todo) file.

---

- Allow a link to be attached to a file that can link to where to get the most up-to-date version.
- Add the option to update files that are there. Like an in-place upgrade if there's a new version.