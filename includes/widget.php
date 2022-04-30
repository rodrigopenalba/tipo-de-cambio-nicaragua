<?php
class tc_nicaragua_widget extends WP_Widget 
{
    function tc_nicaragua_widget()
    {
        $configuracion = array(
            'classname' => 'tc_nicaragua_widget', 
            'description' => __('Tipo de cambio de dólar a córdoba nicaragüense. Información sobre el tipo de cambios de ayer, hoy, mañana y promedio del mes actual.', 'tipo-de-cambio-nicaragua') 
        );
        $this->WP_Widget('tc_nicaragua_widget', __('Tipo de cambio Nicaragua', 'tipo-de-cambio-nicaragua'), $configuracion);
    }

    function widget($args, $instance)
    {
        echo $args['before_widget'];

        $servicio = wp_remote_get( 'https://api.binarylemon.net/tipocambio/hoy' );        
        $estado   = wp_remote_retrieve_response_code( $servicio );
        $json     = json_decode( wp_remote_retrieve_body( $servicio ), true );
 
        $ni_valor_h  = number_format(0.00, 4);
        $ni_fecha_h  = date('Y-m-d');
        $ni_valor_a  = number_format(0.00, 4);
        $ni_fecha_a  = date('Y-m-d');
        $ni_valor_m  = number_format(0.00, 4);
        $ni_fecha_m  = date('Y-m-d');
        $ni_valor_p  = number_format(0.00, 4);
        $ni_fecha_p  = 'Mes';

        if ( 200 == $estado )
        {
            $ni_valor_h  = $json['ni_valor_h'];
            $ni_fecha_h  = $json['ni_fecha_h'];
            $ni_valor_a  = $json['ni_valor_a'];
            $ni_fecha_a  = $json['ni_fecha_a'];
            $ni_valor_m  = $json['ni_valor_m'];
            $ni_fecha_m  = $json['ni_fecha_m'];
            $ni_valor_p  = $json['ni_valor_p'];
            $ni_fecha_p  = $json['ni_fecha_p'];
        } 

        ?>

        <div class="c-container">
            <div class="c-header c-center">            
                <h5 class="c-title ">
                    <?php $titulo = (empty($instance["tc_nicaragua_titulo"])) ? __('TIPO DE CAMBIO OFICIAL NICARAGUA' , 'tipo-de-cambio-nicaragua') : $instance["tc_nicaragua_titulo"]; ?>
                    <b><?php esc_html_e( $titulo ); ?></b>
                </h5>
                <?php if(!empty($instance["tc_nicaragua_subtitulo"])){ ?>
                    <p class="c-sub-title"><?php esc_html_e( $instance["tc_nicaragua_subtitulo"] ); ?></p>
                <?php } ?>

                <?php if(!empty($instance["tc_nicaragua_activar"])){ ?>
                    <img class="c-img" src="<?php esc_url( _e( plugin_dir_url( __FILE__ ).'img/banco-central-de-nicaragua.svg') );?>" alt="">
                <?php } ?>            
            </div>
            <div class="c-body n-text-justify">
                <div class="c-center">
                    <h4 class="c-title">C$ <?php esc_html_e( $ni_valor_h );?></h4>
                    <p><b><?php _e('HOY', 'tipo-de-cambio-nicaragua'); ?></b> (<?php esc_html_e( $this->tcn_formatear_fecha($ni_fecha_h) ); ?>)</p>
                </div>

                <?php if(!empty($instance["tc_nicaragua_detalle"])){ ?>
                    <table class="c-table" cellspacing="0" cellpadding="0">
                        <tr class="c-tr">
                            <td class="c-td-left">
                                <?php _e('AYER', 'tipo-de-cambio-nicaragua'); ?> 
                                <small>(<?php esc_html_e( $this->tcn_formatear_fecha($ni_fecha_a) ); ?>)</small>
                            </td>
                            <td class="c-td-right c-right">
                                <strong>C$ <?php esc_html_e( $ni_valor_a );?></strong>
                            </td>
                        </tr>
                        <tr class="c-tr">
                            <td class="c-td-left">
                                <?php _e('MAÑANA', 'tipo-de-cambio-nicaragua'); ?> 
                                <small>(<?php esc_html_e( $this->tcn_formatear_fecha($ni_fecha_m) ); ?>)</small>
                            </td>
                            <td class="c-td-right c-right">
                                <strong>C$ <?php esc_html_e( $ni_valor_m );?></strong>
                            </td>
                        </tr>
                        <tr class="c-tr">
                            <td class="c-td-left">
                                <?php _e('PROMEDIO', 'tipo-de-cambio-nicaragua'); ?> 
                                <small>(<?php esc_html_e( $this->tcn_formatear_mes( $ni_fecha_p ) ); ?>)</small>
                            </td>
                            <td class="c-td-right c-right">
                                <strong>C$ <?php esc_html_e( $ni_valor_p );?></strong>
                            </td>
                        </tr>
                    </table>   
                <?php } ?>                             
            </div>
            <div class="c-footer">
                <small class="c-small"><?php _e('Fuente: Banco Central de Nicaragua', 'tipo-de-cambio-nicaragua'); ?></small> 
            </div>
        </div>
        <?php
        echo $args['after_widget']; 
    }

    function update($tc_nicaragua_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance["tc_nicaragua_titulo"]    = sanitize_text_field($tc_nicaragua_instance["tc_nicaragua_titulo"]);
        $instance["tc_nicaragua_subtitulo"] = sanitize_text_field($tc_nicaragua_instance["tc_nicaragua_subtitulo"]);
        $instance["tc_nicaragua_activar"]   = $tc_nicaragua_instance["tc_nicaragua_activar"];
        $instance["tc_nicaragua_detalle"]   = $tc_nicaragua_instance["tc_nicaragua_detalle"];
        return $instance;
    }

    function form($instance)
    {
    ?>
        <div>
            <label for="<? esc_attr_e($this->get_field_id('tc_nicaragua_titulo')); ?>"><b><?php _e('Título', 'tipo-de-cambio-nicaragua'); ?>:</b> </label>
            <input id="<?php esc_attr_e($this->get_field_id('tc_nicaragua_titulo')); ?>" name="<?php esc_attr_e($this->get_field_name('tc_nicaragua_titulo')); ?>" type="text" value="<?php esc_attr_e($instance["tc_nicaragua_titulo"]); ?>" />
        </div>

        <div>
            <label for="<?php esc_attr_e($this->get_field_id('tc_nicaragua_subtitulo')); ?>"><b><?php _e('Sub título', 'tipo-de-cambio-nicaragua'); ?>:</b> </label>
            <input id="<?php esc_attr_e($this->get_field_id('tc_nicaragua_subtitulo')); ?>" name="<?php esc_attr_e($this->get_field_name('tc_nicaragua_subtitulo')); ?>" type="text" value="<?php esc_attr_e($instance["tc_nicaragua_subtitulo"]); ?>" />
        </div>

        <?php $tc_nicaragua_activar = isset( $instance['tc_nicaragua_activar'] ) ? $instance['tc_nicaragua_activar'] : 1; ?>

        <div>
            <label for="<?php esc_attr_e($this->get_field_id('tc_nicaragua_activar')); ?>"><b><?php _e('Logo', 'tipo-de-cambio-nicaragua'); ?>:</b></label><br>
            <?php $v_activar = 1; ?>
            <input value="<?php esc_attr_e($v_activar); ?>" id="<?php esc_attr_e($this->get_field_id($v_activar)); ?>" name="<?php esc_attr_e($this->get_field_name('tc_nicaragua_activar')); ?>" type="radio" <?php esc_attr_e($tc_nicaragua_activar == $v_activar ? ' checked="checked"' : ''); ?> />
            <label for="<?php esc_attr_e($this->get_field_id($v_activar)); ?>"><?php _e('Habilitar', 'tipo-de-cambio-nicaragua'); ?></label>
            <br/>

            <?php $v_activar = 0; ?>
            <input value="<?php esc_attr_e($v_activar); ?>" id="<?php esc_attr_e($this->get_field_id($v_activar)); ?>" name="<?php esc_attr_e($this->get_field_name('tc_nicaragua_activar')); ?>" type="radio" <?php esc_attr_e($tc_nicaragua_activar == $v_activar ? ' checked="checked"' : ''); ?> />
            <label for="<?php esc_attr_e($this->get_field_id($v_activar)); ?>"><?php _e('Deshabilitar', 'tipo-de-cambio-nicaragua'); ?></label>
            <br/>
        </div>

        <?php $tc_nicaragua_detalle = isset( $instance['tc_nicaragua_detalle'] ) ? $instance['tc_nicaragua_detalle'] : 1; ?>

        <div>
            <label for="<?php esc_attr_e($this->get_field_id('tc_nicaragua_detalle')); ?>"><b><?php _e('Información detallada (ayer, mañana y promedio)', 'tipo-de-cambio-nicaragua'); ?>:</b></label><br>
            <?php $v_detalle = 1; ?>
            <input value="<?php esc_attr_e($v_detalle); ?>" id="<?php esc_attr_e($this->get_field_id($v_detalle)); ?>" name="<?php esc_attr_e($this->get_field_name('tc_nicaragua_detalle')); ?>" type="radio" <?php esc_attr_e($tc_nicaragua_detalle == $v_detalle ? ' checked="checked"' : ''); ?> />
            <label for="<?php esc_attr_e($this->get_field_id($value)); ?>"><?php _e('Habilitar', 'tipo-de-cambio-nicaragua'); ?></label>
            <br/>

            <?php $v_detalle = 0; ?>
            <input value="<?php esc_attr_e($v_detalle); ?>" id="<?php esc_attr_e($this->get_field_id($v_detalle)); ?>" name="<?php esc_attr_e($this->get_field_name('tc_nicaragua_detalle')); ?>" type="radio" <?php esc_attr_e($tc_nicaragua_detalle == $v_detalle ? ' checked="checked"' : ''); ?> />
            <label for="<?php esc_attr_e($this->get_field_id($value)); ?>"><?php _e('Deshabilitar', 'tipo-de-cambio-nicaragua'); ?></label>
            <br/>
        </div>
    <?php
    }

    function tcn_formatear_fecha($fecha)
    {
        $f = explode('-', $fecha);
  
        switch ($f[1])
        {
            case 1:  $nombre_mes = __('ENE', 'tipo-de-cambio-nicaragua'); break;
            case 2:  $nombre_mes = __('FEB', 'tipo-de-cambio-nicaragua'); break;
            case 3:  $nombre_mes = __('MAR', 'tipo-de-cambio-nicaragua'); break;
            case 4:  $nombre_mes = __('ABR', 'tipo-de-cambio-nicaragua'); break;
            case 5:  $nombre_mes = __('MAY', 'tipo-de-cambio-nicaragua'); break;
            case 6:  $nombre_mes = __('JUN', 'tipo-de-cambio-nicaragua'); break;
            case 7:  $nombre_mes = __('JUL', 'tipo-de-cambio-nicaragua'); break;
            case 8:  $nombre_mes = __('AGO', 'tipo-de-cambio-nicaragua'); break;
            case 9:  $nombre_mes = __('SEP', 'tipo-de-cambio-nicaragua'); break;
            case 10: $nombre_mes = __('OCT', 'tipo-de-cambio-nicaragua'); break;
            case 11: $nombre_mes = __('NOV', 'tipo-de-cambio-nicaragua'); break;
            case 12: $nombre_mes = __('DIC', 'tipo-de-cambio-nicaragua'); break;
            default: $nombre_mes = __('MES', 'tipo-de-cambio-nicaragua'); break;
        }

        return $f[2].'-'.$nombre_mes.'-'.$f[0];
    }

    function tcn_formatear_mes($mes)
    {
        switch ($mes)
        {
            case 'Enero':  return __('Enero', 'tipo-de-cambio-nicaragua'); break;
            case 'Febrero':  return __('Febrero', 'tipo-de-cambio-nicaragua'); break;
            case 'Marzo':  return __('Marzo', 'tipo-de-cambio-nicaragua'); break;
            case 'Abril':  return __('Abril', 'tipo-de-cambio-nicaragua'); break;
            case 'Mayo':  return __('Mayo', 'tipo-de-cambio-nicaragua'); break;
            case 'Junio':  return __('Junio', 'tipo-de-cambio-nicaragua'); break;
            case 'Julio':  return __('Julio', 'tipo-de-cambio-nicaragua'); break;
            case 'Agosto':  return __('Agosto', 'tipo-de-cambio-nicaragua'); break;
            case 'Septiembre':  return __('Septiembre', 'tipo-de-cambio-nicaragua'); break;
            case 'Octubre': return __('Octubre', 'tipo-de-cambio-nicaragua'); break;
            case 'Noviembre': return __('Noviembre', 'tipo-de-cambio-nicaragua'); break;
            case 'Diciembre': return __('Diciembre', 'tipo-de-cambio-nicaragua'); break;
            default: return __('Mes', 'tipo-de-cambio-nicaragua'); break;
        }
    }
}
?>