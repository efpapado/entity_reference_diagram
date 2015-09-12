-- SUMMARY --

The initiative is to have an easy visual diagram of entity references.


-- INSTALLATION --

  1. Download and enable the module.
  2. View at Administer > Structure > Entity reference diagram

-- Why it uses orgFlow --

As entity reference field is very convenient, it ends up with loose respect on database normalizaion, integrity, etc. For instance, You can make a content type referencing to itself, to multiple content type. You can add this existing reference field to other entity. If continuing of doing these, it's very easy to get a mess.

I used orgChart, in this case, the diagram will only show a tree structure. For those entity who has multi parents, the diagram will create duplicated dummy to deal with it. It also provides a filter for selecting the fields you wish to be on the diagram. It helps on making the diagram cleaner and more meaningful.