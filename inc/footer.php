<?php ?>
<footer>
  <div class="half">
    <?php
    if($homeactive == '') {
      ?>
      <p class="left"><a href="#top" class="getmore">back to top</a></p>
      <?
    } ?>
    <p><b>&copy; copyright 2017 Megan Goodacre</b></p>
    <!-- <p>powered by behance</p> -->
  </div>

</footer>
</div>

<!-- End Document
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
<script>
function pinIt()
{
  var e = document.createElement('script');
  e.setAttribute('type','text/javascript');
  e.setAttribute('charset','UTF-8');
  e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
  document.body.appendChild(e);
}
</script>
</body>
</html>
