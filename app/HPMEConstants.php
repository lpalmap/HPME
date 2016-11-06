<?php
namespace App;

class HPMEConstants
{
    //Planificacion querys 
    const META_PROYECTO_QUERY="select m.ide_meta,m.nombre from cfg_meta m where not EXISTS (SELECT p.ide_meta from pln_proyecto_meta p where p.ide_proyecto=:ideProyecto and p.ide_meta=m.ide_meta)";
    const OBJETIVO_META_QUERY="SELECT o.ide_objetivo,o.nombre FROM cfg_objetivo o WHERE NOT EXISTS (SELECT m.ide_objetivo FROM pln_objetivo_meta m WHERE m.ide_objetivo=o.ide_objetivo and m.ide_proyecto=:ideProyecto)";
    const AREA_OBJETIVO_QUERY="SELECT a.ide_area,a.nombre FROM cfg_area_atencion a WHERE NOT EXISTS(SELECT o.ide_area FROM pln_area_objetivo o WHERE o.ide_area=a.ide_area AND o.ide_proyecto=:ideProyecto)";
    const INDICADOR_AREA_QUERY="SELECT i.ide_indicador,i.nombre FROM cfg_indicador i WHERE NOT EXISTS(SELECT a.ide_indicador FROM pln_indicador_area a WHERE a.ide_indicador=i.ide_indicador and a.ide_proyecto=:ideProyecto)";
    const PRODUCTO_INDICADOR_QUERY="SELECT p.ide_producto,p.nombre FROM cfg_producto p WHERE NOT EXISTS(SELECT i.ide_proyecto FROM pln_producto_indicador i where i.ide_producto=p.ide_producto and i.ide_proyecto=:ideProyecto)";
    const USUARIO_REGION_QUERY="SELECT s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s WHERE NOT EXISTS (SELECT u.ide_usuario FROM seg_usuario_region u WHERE u.ide_usuario=s.ide_usuario) order by s.usuario";
    //const USUARIO_REGION_QUERY_EDIT="SELECT s.ide_usuario,s.usuario,s.nombres,s.apellidos FROM seg_usuario s WHERE NOT EXISTS (SELECT u.ide_usuario FROM seg_usuario_region u WHERE (u.ide_usuario=s.ide_usuario or s.ide_usuario=:ideUsuario)) order by s.usuario";
    const NOMBRE_ROL_POR_USUARIO="select r.nombre from seg_usuario_rol ur,seg_rol r where ur.ide_usuario=:ideUsuario and r.ide_rol=ur.ide_rol";
    const REGIONES_PROYECTO_QUERY="SELECT r.ide_region,r.nombre FROM cfg_region r WHERE NOT EXISTS(SELECT pr.ide_region FROM cfg_proyecto_region pr where pr.ide_region=r.ide_region AND pr.ide_proyecto=:ideProyecto)";
    const REGION_USUARIO_ADMINISTRADOR_QUERY=" SELECT ide_region FROM seg_usuario_region WHERE ide_usuario=:ideUsuario";
    const ULTIMO_PROYECTO_ABIERTO_QUERY="SELECT p.ide_proyecto FROM pln_proyecto_planificacion p WHERE p.estado=:estado";
    
    //Querys reportes de planificacion
    const PROYECTOS_REGION_QUERY="SELECT p.ide_proyecto_region,r.nombre,u.usuario,DATE_FORMAT(p.fecha_ingreso,'%d-%m-%Y') as fecha_ingreso,DATE_FORMAT(p.fecha_aprobacion,'%d-%m-%Y') as fecha_aprobacion,p.estado FROM pln_proyecto_region p,cfg_region r,seg_usuario u WHERE p.ide_region=r.ide_region and p.ide_usuario_creacion=u.ide_usuario and p.ide_proyecto_planificacion=:ideProyecto ORDER BY r.nombre asc";
    const PLN_METAS_POR_PROYECTO="SELECT p.ide_proyecto_meta,m.nombre FROM pln_proyecto_meta p,cfg_meta m WHERE p.ide_proyecto=:ideProyecto and p.ide_meta=m.ide_meta";
    const PLN_OBJETIVOS_POR_META="SELECT p.ide_objetivo_meta,o.nombre FROM pln_objetivo_meta p,cfg_objetivo o WHERE o.ide_objetivo=p.ide_objetivo and p.ide_proyecto_meta=:ideProyectoMeta ORDER BY o.nombre";
    const PLN_AREAS_POR_OBJETIVO="SELECT p.ide_area_objetivo,a.nombre FROM pln_area_objetivo p,cfg_area_atencion a where a.ide_area=p.ide_area and p.ide_objetivo_meta=:ideObjetivoMeta ORDER BY a.nombre";
    
    
    const SI='S';
    const NO='N';
    const ABIERTO='ABIERTO';
    const CERRADO='CERRADO';
    const PUBLICADO='PUBLICADO';
    const EJECUTADO='EJECUTADO';
    const DATE_FORMAT='Y-m-d';
    const HTTP_AJAX_ERROR=404;
    
}
