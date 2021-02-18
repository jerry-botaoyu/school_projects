/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package image_gui;

import java.awt.Color;
import java.awt.image.BufferedImage;
import jdk.nashorn.internal.codegen.CompilerConstants;

/**
 *
 * @author Botao Yu
 * 
 * FastImgProcessor uses multi-thread to speed up image processing.
 */
public class FastImgProcessor {
    
    //The type of ColorConverter
    //Can either be a ColorConverterGrey or ColorConverterGamma
    private ColorConverter colorConverter;


    public FastImgProcessor() {
    }

    /**
     * constructs a FastImgProcessor using a ColorConverter.
     * @param colorConverter 
     */
    public FastImgProcessor(ColorConverter colorConverter) {
        this.colorConverter = colorConverter;
    }
    
    /**
     * Convert input image using ColorConverter.Convert().
     * The type of ColorConverter will be decided on run time.
     * 
     * @param input image
     * @return the processed image
     */
    public BufferedImage convertImage(BufferedImage input){
         BufferedImage outputImage = new BufferedImage(input.getWidth(), input.getHeight(), input.getType());
        try{
            int threadCount = Runtime.getRuntime().availableProcessors();
            Thread[] threads = new Thread[threadCount];
            
            //Each thread will do the same amount of work except the last thread
            int threadInterationCount = input.getHeight() / threadCount;

            for(int i=0; i<threadCount; ++i){
                final int startIndexHeight = i*threadInterationCount;
                final int endIndexHeight = (i == threadCount-1) ?
                        input.getHeight() :
                        (i+1) * threadInterationCount;
                     
                threads[i] = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        for(int j=startIndexHeight; j<endIndexHeight; ++j){
                            for(int i=0; i < input.getWidth(); ++i){
                                int color = input.getRGB(i, j);
                                
                                int outputColor = colorConverter.convert(color);                         
                                outputImage.setRGB(i, j, 1, 1, 
                                        new int[]{outputColor}, 0, 0);
                            }
                        }       
                    }
                });
                threads[i].start();       
            }
            
            for(int i=0; i < threadCount; ++i){
                threads[i].join();
            }
        }catch(Exception e){}
    return outputImage;
    }
    
    //----------- Methods that just convert 1 color -----------------------

    public interface ColorConverter{
        public abstract int convert(int color);
    }
    
    /**
     * Convert color to grey.
     */
    public class ColorConverterGrey implements ColorConverter{
        
        @Override
        public int convert(int color) {
            //separate the argb values
            int a = (color & 0xFF000000) >> 24;
            int r = (color & 0x00FF0000) >> 16;
            int g = (color & 0x0000FF00) >> 8;
            int b = (color & 0x000000FF);

            int greyScaleValue = (r+g+b)/3;
            
            //since the argb all have the same value, it will be a shade of grey
            int outputColor = a << 24 | 
                    greyScaleValue << 16 | 
                    greyScaleValue << 8 | 
                    greyScaleValue;
            return outputColor;
        }
        
    }
    
    /**
     * Adjust color using gamma.
     */
    public class ColorConverterGamma implements ColorConverter{
        
        private double gamma;

        public ColorConverterGamma(double gamma) {
            this.gamma = gamma;
        }

        @Override
        public int convert(int color) {
           
            int r = (color & 0x00FF0000) >> 16;
            int g = (color & 0x0000FF00) >> 8;
            int b = (color & 0x000000FF);

            float hsb[] = Color.RGBtoHSB(r, g, b, null);
            
            //adjust hsb using gamma
            hsb[2] = (float) (Math.pow(hsb[2], gamma));
            int outputColor = Color.HSBtoRGB(hsb[0], hsb[1], hsb[2]);
            return outputColor;
        }
        
    }
 
}
