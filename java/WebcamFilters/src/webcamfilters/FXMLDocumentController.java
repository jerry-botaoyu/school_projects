/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package webcamfilters;

import java.awt.List;
import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.ResourceBundle;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;
import javafx.application.Platform;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.CheckBox;
import javafx.scene.control.ComboBox;
import javafx.scene.control.Label;
import javafx.scene.control.Slider;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;

/**
 *
 * @author bergeron
 */
public class FXMLDocumentController implements Initializable {
    @FXML
    private ImageView webcamImageView;
    
    @FXML
    private Button startWebcamButton;
    
    @FXML
    private CheckBox houghCirclesCheckBox;
    
    @FXML
    private CheckBox cannyEdgeDetectionCheckBox;
    
    @FXML
    private CheckBox rotateCheckBox;
    
     @FXML
    private CheckBox dilateCheckBox;
    
    @FXML
    private Slider cannyThresholdSlider;

    @FXML
    private Slider cannyBlurSlider;

    @FXML
    private CheckBox greyScalesCheckBox;       

    @FXML
    private Label messageLabel;
    
    @FXML
    private CheckBox applyColorFilterCheckBox;  
    
    @FXML
    private ComboBox colorMapComboBox;
    
    @FXML
    private Slider rotationSlider;
    
    @FXML
    private CheckBox laplacianCheckBox;
    
    @FXML
    private Slider laplacianSlider;
    
    @FXML
    private Slider dilateSlider;
    
    

    // Video Capture Status
    private boolean cameraActive = false;
    ScheduledExecutorService timer = null;
    
    ArrayList<String> colorMapList = new ArrayList<>();
    
    String[] colorMaps = new String[]{
            
        };
    
    @FXML
    private void handleStartWebcamButtonAction(ActionEvent event) {
        if (cameraActive == false)
        {
            // Start grabbing video feed from webcam
            // This executes on a separate thread to not block the GUI

            startWebcamButton.setText("Stop Webcam");
            cameraActive = true;
            messageLabel.setText("Webcam started...");

            // Refresh image 30 times per second / every 33ms
            timer = Executors.newSingleThreadScheduledExecutor();
            timer.scheduleAtFixedRate(new Runnable(){
                @Override
                public void run() {
                    //setting the flags
                    int ipFlags = 0;
                    
                    if (greyScalesCheckBox.isSelected())
                        ipFlags |= VideoProcessing.CONVERT_TO_GREYSCALES_FLAG;
                    
                    if (cannyEdgeDetectionCheckBox.isSelected())
                        ipFlags |= VideoProcessing.CANNY_EDGE_DETECTION_FLAG;
                    
                    if(applyColorFilterCheckBox.isSelected())
                        ipFlags |= VideoProcessing.CONVERT_COLOR_FLAG;  
                    
                    if(rotateCheckBox.isSelected())
                        ipFlags |= VideoProcessing.ROTATE_FLAG;
                    
                    if(dilateCheckBox.isSelected())
                        ipFlags |= VideoProcessing.DILATE_FLAG;
                                                                     
                    if(laplacianCheckBox.isSelected())
                        ipFlags |= VideoProcessing.LAPLACIAN_FLAG;
                    
                    int cannyBlur = (int)cannyBlurSlider.getValue();
                    if (cannyBlur%2 == 0) ++cannyBlur;
                    
                    //setting the values
                    String colorMapComboBoxValue = (String)colorMapComboBox.valueProperty().getValue();
                    int colorMapIndex = colorMapList.indexOf(colorMapComboBoxValue);
                    
                    int rotationDegree = (int) rotationSlider.getValue();
                    
                    int laplacianValue = (int) laplacianSlider.getValue();
                    
                    int dilateValue =  (int) dilateSlider.getValue();
                    
                    Image fxImage = VideoProcessing.processVideoFrame(
                                              ipFlags, cannyBlur, 
                                              cannyThresholdSlider.getValue(), colorMapIndex, 
                                              rotationDegree, laplacianValue,
                                              dilateValue);
                    webcamImageView.setImage(fxImage);
                }
            }, 0, 33, TimeUnit.MILLISECONDS); 
        }
        else
        {
            // Stop grabbing video feed from webcam
            startWebcamButton.setText("Start Webcam");
            cameraActive = false;
            messageLabel.setText("Webcam Stopped");


            // Stop timer
            try
            {
                timer.shutdown();
                timer.awaitTermination(2, TimeUnit.SECONDS);
                timer = null;
            }
            catch(Exception e)
            {
                System.out.println("Error shutting down timer!");
            }
            webcamImageView.setImage(null);
        }
    }

    
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        VideoProcessing.initializeVideoCapture();
    
        colorMapList.add("COLORMAP_AUTUMN");
        colorMapList.add("COLORMAP_BONE");
        colorMapList.add("COLORMAP_JET");
        colorMapList.add("COLORMAP_WINTER");
        colorMapList.add("COLORMAP_RAINBOW");
        colorMapList.add("COLORMAP_OCEAN");
        colorMapList.add("COLORMAP_SUMMER");
        colorMapList.add("COLORMAP_SPRING");
        colorMapList.add("COLORMAP_COOL");
        colorMapList.add("COLORMAP_HSV");
        colorMapList.add("COLORMAP_PINK");
        colorMapList.add("COLORMAP_HOT");
        colorMapList.add("COLORMAP_PARULA");
        colorMapList.add("COLORMAP_MAGMA");
        colorMapList.add("COLORMAP_INFERNO");
        colorMapList.add("COLORMAP_PLASMA");
        colorMapList.add("COLORMAP_VIRIDIS");
        colorMapList.add("COLORMAP_CIVIDIS");
        colorMapList.add("COLORMAP_TWILIGHT");
        colorMapList.add("COLORMAP_TWILIGHT_SHIFTED");

        for(int i = 0; i < colorMapList.size(); i++)
            colorMapComboBox.getItems().add(colorMapList.get(i));
        
        colorMapComboBox.setValue(colorMapList.get(0));
        
        
    }        
    
     public void shutdown() {
        
        if (timer != null)
        {
            try
            {
                timer.shutdown();
                timer.awaitTermination(2, TimeUnit.SECONDS);
                timer = null;
            }
            catch(Exception e)
            {
                System.out.println("Error stopping timer!");
            }
        }
        
        VideoProcessing.shutdownVideoCapture();
        Platform.exit();
    }
}
