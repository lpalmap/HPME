<?php
namespace App;

class HPMEConstants
{
    //Planificacion querys 
    const META_PROYECTO_QUERY="select m.ide_meta,m.nombre from cfg_meta m where not EXISTS (SELECT p.ide_meta from pln_proyecto_meta p where p.ide_proyecto=:ideProyecto and p.ide_meta=m.ide_meta)";
    const OBJETIVO_META_QUERY="SELECT o.ide_objetivo,o.nombre FROM cfg_objetivo o WHERE NOT EXISTS (SELECT m.ide_objetivo FROM pln_objetivo_meta m WHERE m.ide_objetivo=o.ide_objetivo and m.ide_proyecto_meta=:ideProyectoMeta)";
    const AREA_OBJETIVO_QUERY="SELECT a.ide_area,a.nombre FROM cfg_area_atencion a WHERE NOT EXISTS(SELECT o.ide_area FROM pln_area_objetivo o WHERE o.ide_area=a.ide_area AND o.ide_objetivo_meta=:ideObjetivoMeta)";
    const INDICADOR_AREA_QUERY="SELECT i.ide_indicador,i.nombre FROM cfg_indicador i WHERE NOT EXISTS(SELECT a.ide_indicador FROM pln_indicador_area a WHERE a.ide_indicador=i.ide_indicador and a.ide_area_objetivo=:ideAreaObjetivo)";
    const PRODUCTO_INDICADOR_QUERY="SELECT p.ide_producto,p.nombre FROM cfg_producto p WHERE NOT EXISTS(SELECT i.ide_proyecto FROM pln_producto_indicador i where i.ide_producto=p.ide_producto and i.ide_indicador_area=:ideIndicadorArea)";
    const USUARIO_REGION_QUERY="SELECT s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s WHERE NOT EXISTS (SELECT u.ide_usuario FROM seg_usuario_region u WHERE u.ide_usuario=s.ide_usuario) order by s.usuario";
    //const USUARIO_REGION_QUERY_EDIT="SELECT s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s WHERE NOT EXISTS (SELECT u.ide_usuario FROM seg_usuario_region u WHERE (u.ide_usuario=s.ide_usuario or s.ide_usuario=:ideUsuario)) order by s.usuario";
    const NOMBRE_ROL_POR_USUARIO="select r.nombre from seg_usuario_rol ur,seg_rol r where ur.ide_usuario=:ideUsuario and r.ide_rol=ur.ide_rol";
    const REGIONES_PROYECTO_QUERY="SELECT r.ide_region,r.nombre FROM cfg_region r WHERE NOT EXISTS(SELECT pr.ide_region FROM cfg_proyecto_region pr where pr.ide_region=r.ide_region AND pr.ide_proyecto=:ideProyecto)";
    const SI='S';
    const NO='N';
    const ABIERTO='ABIERTO';
    const CERRADO='CERRADO';
    const PUBLICADO='PUBLICADO';
    const EJECUTADO='EJECUTADO';
    const DATE_FORMAT='Y-m-d';
    const HTTP_AJAX_ERROR=404;
    
}
