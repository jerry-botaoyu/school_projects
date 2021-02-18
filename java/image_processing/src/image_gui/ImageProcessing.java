package image_gui;

import java.awt.Color;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import javax.imageio.ImageIO;

public class ImageProcessing {

    public static BufferedImage convertToGreyScale(final BufferedImage img) {
        BufferedImage output = new BufferedImage(img.getWidth(), img.getHeight(), img.getType());

        for (int j = 0; j < img.getHeight(); ++j) {
            for (int i = 0; i < img.getWidth(); ++i) {
                int argb = img.getRGB(i, j);

                int alpha = (argb & 0xFF000000) >> 24;
                int red = (argb & 0x00FF0000) >> 16;
                int green = (argb & 0x0000FF00) >> 8;
                int blue = (argb & 0x000000FF);

                int grey = (red + green + blue) / 3;

                int greyARGB = alpha << 24 | grey << 16 | grey << 8 | grey;

                output.setRGB(i, j, greyARGB);
            }
        }

        return output;
    }

    public static BufferedImage applyGammaCorrection(BufferedImage img, float gamma) {
        BufferedImage output = new BufferedImage(img.getWidth(), img.getHeight(), img.getType());

        for (int j = 0; j < img.getHeight(); ++j) {
            for (int i = 0; i < img.getWidth(); ++i) {
                int argb = img.getRGB(i, j);

                int alpha = (argb & 0xFF000000) >> 24;
                int red = (argb & 0x00FF0000) >> 16;
                int green = (argb & 0x0000FF00) >> 8;
                int blue = (argb & 0x000000FF);

                float[] hsb = Color.RGBtoHSB(red, green, blue, null);
                hsb[2] = (float) (Math.pow(hsb[2], gamma));

                int rgb = Color.HSBtoRGB(hsb[0], hsb[1], hsb[2]);
                output.setRGB(i, j, rgb);
            }
        }

        return output;
    }

    private static final float gaussianKernel[][] = new float[][]{
            {0.00000067f, 0.00002292f, 0.00019117f, 0.00038771f, 0.00019117f, 0.00002292f, 0.00000067f},
            {0.00002292f, 0.00078634f, 0.00655965f, 0.01330373f, 0.00655965f, 0.00078633f, 0.00002292f},
            {0.00019117f, 0.00655965f, 0.05472157f, 0.11098164f, 0.05472157f, 0.00655965f, 0.00019117f},
            {0.00038771f, 0.01330373f, 0.11098164f, 0.22508352f, 0.11098164f, 0.01330373f, 0.00038771f},
            {0.00019117f, 0.00655965f, 0.05472157f, 0.11098164f, 0.05472157f, 0.00655965f, 0.00019117f},
            {0.00002292f, 0.00078634f, 0.00655965f, 0.01330373f, 0.00655965f, 0.00078633f, 0.00002292f},
            {0.00000067f, 0.00002292f, 0.00019117f, 0.00038771f, 0.00019117f, 0.00002292f, 0.00000067f}
        };
    
    public static BufferedImage applyGaussianBlur(BufferedImage img) {
        return applyConvolutionFilterFaster(img, gaussianKernel);
    }
    
     public static BufferedImage applyGaussianBlurBenchMarking(BufferedImage img) {
        return applyConvolutionFilterFasterBenchMarking(img, gaussianKernel);
    }

    private static BufferedImage applyConvolutionFilter(BufferedImage img, float[][] kernel) {
        BufferedImage output = new BufferedImage(img.getWidth(), img.getHeight(), img.getType());

        // Set output image from input image (img)
        for (int j = 0; j < img.getHeight(); ++j) {
            for (int i = 0; i < img.getWidth(); ++i) {
                // Calculate the gaussian blur
                Color pixelColor = applyKernel(img, kernel, i, j);
                output.setRGB(i, j, pixelColor.getRGB());
            }
        }

        return output;
    }

    private static BufferedImage applyConvolutionFilterFaster(BufferedImage img, float[][] kernel) {
        BufferedImage output = new BufferedImage(img.getWidth(), img.getHeight(), img.getType());

        try {
            int threadCount = Runtime.getRuntime().availableProcessors();
            //System.out.println("available: " + threadCount);
            Thread[] threads = new Thread[threadCount];
            int threadInterationCount = img.getHeight() / threadCount;

            for (int i = 0; i < threadCount; ++i) {
                final int startIndexHeight = i * threadInterationCount;
                final int endIndexHeight = (i == threadCount - 1)
                        ? img.getHeight()
                        : (i + 1) * threadInterationCount;

                threads[i] = new Thread(new Runnable() {
                    @Override
                    public void run() {
                        for (int j = startIndexHeight; j < endIndexHeight; ++j) {
                            for (int i = 0; i < img.getWidth(); ++i) {

                                // Calculate the gaussian blur
                                Color pixelColor = applyKernel(img, kernel, i, j);
                                output.setRGB(i, j, pixelColor.getRGB());
                            }

                        }
                    }
                });
                threads[i].start();
            }
            for (int i = 0; i < threadCount; ++i) {
                threads[i].join();
            }
        } catch (Exception e) {
        }

        return output;
    }

    private static BufferedImage applyConvolutionFilterFasterBenchMarking(BufferedImage img, float[][] kernel) {

        int[] threadCountArray = new int[]{1, 2, 3, 4, 5, 6, 7, 8, 16};
        BufferedImage finalOutput = null;
        for (int a = 0; a < threadCountArray.length; a++) {
            long startTime = System.currentTimeMillis();
            
            BufferedImage output = new BufferedImage(img.getWidth(), img.getHeight(), img.getType());

            try {
                int threadCount = threadCountArray[a];
                //System.out.println("available: " + threadCount);
                Thread[] threads = new Thread[threadCount];
                int threadInterationCount = img.getHeight() / threadCount;

                for (int i = 0; i < threadCount; ++i) {
                    final int startIndexHeight = i * threadInterationCount;
                    final int endIndexHeight = (i == threadCount - 1)
                            ? img.getHeight()
                            : (i + 1) * threadInterationCount;

                    threads[i] = new Thread(new Runnable() {
                        @Override
                        public void run() {
                            for (int j = startIndexHeight; j < endIndexHeight; ++j) {
                                for (int i = 0; i < img.getWidth(); ++i) {

                                    // Calculate the gaussian blur
                                    Color pixelColor = applyKernel(img, kernel, i, j);
                                    output.setRGB(i, j, pixelColor.getRGB());
                                }

                            }
                        }
                    });
                    threads[i].start();
                }
                for (int i = 0; i < threadCount; ++i) {
                    threads[i].join();
                }
            } catch (Exception e) {
            }
            long endTime = System.currentTimeMillis();
            long timeTaken = endTime - startTime;
            System.out.println("Number of threads is " + threadCountArray[a]
                    + ", time taken is " +timeTaken +" ms");
            finalOutput = output;
        }

        return finalOutput;
    }

    private static Color applyKernel(BufferedImage img, float[][] kernel, int row, int column) {
        float red = 0.0f;
        float green = 0.0f;
        float blue = 0.0f;

        int minIndexH = -(kernel.length / 2);
        int maxIndexH = minIndexH + kernel.length;
        int minIndexV = -(kernel[0].length / 2);
        int maxIndexV = minIndexV + kernel[0].length;

        for (int i = minIndexH; i < maxIndexH; ++i) {
            for (int j = minIndexV; j < maxIndexV; ++j) {
                int indexH = wrapHorizontalIndex(img, row + i);
                int indexV = wrapVerticalIndex(img, column + j);
                Color col = new Color(img.getRGB(indexH, indexV));

                red += col.getRed() * kernel[i - minIndexH][j - minIndexV];
                green += col.getGreen() * kernel[i - minIndexH][j - minIndexV];
                blue += col.getBlue() * kernel[i - minIndexH][j - minIndexV];
            }
        }

        red = Math.min(Math.max(red, 0.0f), 255.0f);
        green = Math.min(Math.max(green, 0.0f), 255.0f);
        blue = Math.min(Math.max(blue, 0.0f), 255.0f);

        return new Color((int) red, (int) green, (int) blue);
    }

    public static BufferedImage applySharpen(BufferedImage input) {
        final float edgeKernel[][] = new float[][]{
            {0f, -1f, 0f},
            {-1f, 5f, -1f},
            {0f, -1f, 0f},};

        return applyConvolutionFilter(input, edgeKernel);
    }

    private static int wrapHorizontalIndex(BufferedImage img, int i) {
        // This takes care of negative and positive indices beyond the image resolution
        return (i + img.getWidth()) % img.getWidth();
    }

    private static int wrapVerticalIndex(BufferedImage img, int i) {
        // This takes care of negative and positive indices beyond the image resolution
        return (i + img.getHeight()) % img.getHeight();
    }

}
