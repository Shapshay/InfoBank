<script>
    $(function(){
        {PROGRES_ANIM}
    });

    function ProgressAnimate(prog_id) {
        var input = $('#progbar'+prog_id),
                bar = $('#bar'+prog_id),
                bw = bar.width(),
                percent = $('#percent'+prog_id),
                circle = $('#circle'+prog_id),
                ps = percent.find('span'),
                cs = circle.find('span'),
                name = 'rotate';
        var t = input, val = t.val();
        if (val >=0 && val <= 100){
            var w = 100-val, pw = (bw*w)/100,
                    pa = {
                        width: w+'%'
                    },
                    //cw = (bw-pw)/2,
                    cw = (bw-pw),
                    ca = {
                        left: cw
                    }
            ps.animate(pa, 1000);
            cs.text(val+'%');
            circle.animate(ca, function(){
                circle.removeClass(name)
            }).addClass(name);
        } else {
            //alert('range: 0 - 100');
            t.val('');
        }
    }
</script>