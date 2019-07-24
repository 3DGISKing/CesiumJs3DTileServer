# 3DTileServer 

Sometimes we need to host 3dtile in our server.
It can be implemented by simple node js express server.
But you can notice that 3dtilset contains nested folders with thousands of small files.

It is critical if you plan to download and upload 3d tile.

One solution is to use sqlite format which Cesium introduce.

This is a simple 3dtile server based on sqlite format 3dtileset.
Server is based on CodeIgniter-3.1.10

# Important
   ## 1 First, You should make sqlite format 3dtileset.
      How to make sqlite 3d tileset?
      
      https://medium.com/@wugis1219/how-to-make-sqlite-database-from-cesium-3d-tile-file-system-9e22b94cd0f8
      
   ## 2 Then copy it and paste asset folder.
   ## 3 If your tileset have more deep level, then add routing rules in application/config/routes.php
   ## 4 You can remove index.php in tileset.json URL by using rewrite Rule
   
# Example
    var viewer = new Cesium.Viewer('cesiumContainer');

    url = "http://localhost/3DTileServer/index.php/asset/11294/tileset.json";
    //url = "http://localhost/3DTileServer/asset/11294/tileset.json";
    
    var tileset = new Cesium.Cesium3DTileset({
      url: url
    });
    
    viewer.scene.primitives.add(tileset);
    viewer.zoomTo(tileset);  

