<?php
namespace App;

class HPMEConstants
{
    //Planificacion querys 
    const META_PROYECTO_QUERY="select m.ide_meta,m.nombre from cfg_meta m where not EXISTS (SELECT p.ide_meta from pln_proyecto_meta p where p.ide_proyecto=:ideProyecto and p.ide_meta=m.ide_meta)";
    const SI='S';
    const NO='N';
    
}

