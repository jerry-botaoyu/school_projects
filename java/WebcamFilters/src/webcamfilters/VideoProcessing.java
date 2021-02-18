/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package webcamfilters;

import java.awt.image.BufferedImage;
import java.awt.image.DataBufferByte;
import javafx.embed.swing.SwingFXUtils;
import javafx.scene.image.Image;
import org.opencv.core.Core;
import org.opencv.core.CvType;
import org.opencv.core.Mat;
import org.opencv.core.Point;
import org.opencv.core.Scalar;
import org.opencv.core.Size;
import org.opencv.imgproc.Imgproc;
import org.opencv.videoio.VideoCapture;

/**
 *
 * @author cstuser
 */
public class VideoProcessing {
    public final static int CONVERT_TO_GREYSCALES_FLAG      = 1 << 0;
    public final static int CANNY_EDGE_DETECTION_FLAG       = 1 << 1;
    public final static int CONVERT_COLOR_FLAG              = 1 << 2;
    public final static int DILATE_FLAG                     = 1 << 3;
    public final static int ROTATE_FLAG                     = 1 << 4;
    public final static int LAPLACIAN_FLAG                  = 1 << 5;
    

    // add your own flags here ...

    
    private static VideoCapture capture = null;

    public static void initializeVideoCapture()
    {
        capture = new VideoCapture();
        capture.open(0);
    }

    public static void shutdownVideoCapture()
    {
        capture.release();
        capture = null;
    }

    
    public static Mat grabVideoFrame()
    {
        if (capture != null && capture.isOpened())
        {
            Mat matImage = new Mat();
            capture.read(matImage);

            // Convert Image Matrix to FX Image
            if (matImage.empty() == false)
            {
                return matImage;
            }
            else
            {
                System.out.println("Video capture returned a null image...");                
                return null;
            }
        }        
        else
        {
            System.out.println("Video capture is not opened...");
            return null;
        }      
    }
    
    public static BufferedImage grabVideoFrameBufferedImage()
    {
        Mat image = grabVideoFrame();
        return matToBufferedImage(image);
    }

    public static Image grabVideoFrameFxImage()
    {
        BufferedImage image = grabVideoFrameBufferedImage();
        return SwingFXUtils.toFXImage(image, null);
    }
    
    public static Image processVideoFrame(int imageProcessingAlgorithmsFlag, int preCannyBlur, 
            double cannyThreshold, int colorMap, 
            int rotationDegree, int laplacianValue,
            int dilateValue)
    {
        Mat input = grabVideoFrame();

        if ((imageProcessingAlgorithmsFlag & CONVERT_TO_GREYSCALES_FLAG) == CONVERT_TO_GREYSCALES_FLAG)
        {
            Mat output = new Mat(input.width(), input.height(), input.type());
            Imgproc.cvtColor(input, output, Imgproc.COLOR_RGB2GRAY);
            input = output;
        }
        
        if ((imageProcessingAlgorithmsFlag & CANNY_EDGE_DETECTION_FLAG) == CANNY_EDGE_DETECTION_FLAG)
        {
             // You must first apply a median blur on the input image using parameter preCannyBlur
            // Then you must apply the canny edge detection algorithm using the parameter threshold
            Mat output = new Mat(input.width(), input.height(), input.type());
            Imgproc.medianBlur(input, output, preCannyBlur);
            input = output;
            
            Mat outputCannyMat = new Mat(input.width(), input.height(), input.type());
            Imgproc.Canny(input, outputCannyMat, cannyThreshold, cannyThreshold);
            input = outputCannyMat;
            
        }
        
        if((imageProcessingAlgorithmsFlag & CONVERT_COLOR_FLAG) == CONVERT_COLOR_FLAG){

            Mat outputColorMap = new Mat(input.width(), input.height(), input.type());
            Imgproc.applyColorMap(input, outputColorMap, colorMap);
            input = outputColorMap;
        }
        
        if((imageProcessingAlgorithmsFlag & DILATE_FLAG) == DILATE_FLAG){
            Mat outputErode = new Mat(input.width(), input.height(), input.type());
            Mat kernel = Imgproc.getStructuringElement(Imgproc.MORPH_RECT, new Size(dilateValue, dilateValue));
            Imgproc.dilate(input, outputErode, kernel);
            input = outputErode;
        }
        
        if((imageProcessingAlgorithmsFlag & ROTATE_FLAG) == ROTATE_FLAG){
            Mat output = new Mat(input.width(), input.height(), input.type());
            Mat rotationMatrix = Imgproc.getRotationMatrix2D(new Point(300, 200), rotationDegree, 1);
            Imgproc.warpAffine(input, output, rotationMatrix, new Size(input.width(), input.height()));
            input = output;
        }
        
        if((imageProcessingAlgorithmsFlag & LAPLACIAN_FLAG) == LAPLACIAN_FLAG){
             Mat output = new Mat(input.width(), input.height(), input.type());
             if(laplacianValue %2 == 0) laplacianValue++;
             Imgproc.Laplacian(input, output, 0, laplacianValue,laplacianValue);
             input = output;
        }
        
        
        
        return SwingFXUtils.toFXImage(matToBufferedImage(input), null);
    }
    
    
    
    public static Mat bufferedImageToMat(BufferedImage input)
    {
        BufferedImage convertedImg = new BufferedImage(input.getWidth(), input.getHeight(), BufferedImage.TYPE_3BYTE_BGR);
        convertedImg.getGraphics().drawImage(input, 0, 0, null);
        
        Mat mat = new Mat(convertedImg.getHeight(), convertedImg.getWidth(), CvType.CV_8UC3);
        byte[] data = ((DataBufferByte) convertedImg.getRaster().getDataBuffer()).getData();
        mat.put(0, 0, data);
        
        return mat;
    }
    
    public static BufferedImage matToBufferedImage(Mat original)
    {
        BufferedImage image = null;
        int width = original.width(), height = original.height(), channels = original.channels();
        byte[] sourcePixels = new byte[width * height * channels];
        original.get(0, 0, sourcePixels);

        if (original.channels() > 1)
        {
            image = new BufferedImage(width, height, BufferedImage.TYPE_3BYTE_BGR);
        }
        else
        {
            image = new BufferedImage(width, height, BufferedImage.TYPE_BYTE_GRAY);
        }
        final byte[] targetPixels = ((DataBufferByte) image.getRaster().getDataBuffer()).getData();
        System.arraycopy(sourcePixels, 0, targetPixels, 0, sourcePixels.length);

        return image;
    }
    
    public static Mat applyGaussianBlur(Mat input)
    {
        final int kernelSize = 7;
        Mat kernel = Imgproc.getGaussianKernel(kernelSize, 0.2);

        Mat output = new Mat(input.width(), input.height(), input.type());
        Imgproc.filter2D(input, output, -1, kernel);
    
        return output;
    }
    
}
