package spotifyplayer;


import com.sun.javafx.collections.ObservableListWrapper;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.net.URL;
import java.util.ArrayList;
import java.util.ResourceBundle;
import java.util.Timer;
import java.util.TimerTask;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import java.util.logging.Level;
import java.util.logging.Logger;
import javafx.application.Platform;
import javafx.concurrent.Task;
import javafx.embed.swing.SwingFXUtils;
import javafx.event.ActionEvent;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.control.Button;
import javafx.scene.control.Label;
import javafx.scene.control.ProgressIndicator;
import javafx.scene.control.Slider;
import javafx.scene.control.TableCell;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TextField;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.DragEvent;
import javafx.scene.input.MouseEvent;
import javafx.scene.media.Media;
import javafx.scene.media.MediaPlayer;
import javafx.util.Callback;
import javafx.util.Duration;
import javax.imageio.ImageIO;
import javax.swing.JFileChooser;

public class FXMLDocumentController implements Initializable {
    
    @FXML
    private Label artistLabel;
    
    @FXML
    private Label albumLabel;
    
    @FXML
    private ImageView albumCoverImageView;

    @FXML
    private TextField searchArtistTextField;
    
    @FXML
    private Button previousAlbumButton;
    
    @FXML
    private Button nextAlbumButton;
    
    @FXML
    private TableView tracksTableView;

    @FXML
    private Button playButton;
    
    @FXML
    private Slider trackSlider;
    
    @FXML
    private Label trackTimeLabel;
        
    @FXML
    private ProgressIndicator progress;
   
    @FXML
    private Button saveButton;
    
    MediaPlayer mediaPlayer = null;
    int currentAlbum = 0;
    ArrayList<Album> albums = null;

    @FXML
    private void handleSaveButtonAction(ActionEvent event){ 
        Image coverImage = new Image(albums.get(currentAlbum).getImageURL());
        BufferedImage bufferedImage = SwingFXUtils.fromFXImage(coverImage, null);
        
        JFileChooser fileChooser = new JFileChooser();
        fileChooser.setCurrentDirectory(new File("./images"));
        File file = null;
        if (fileChooser.showSaveDialog(null) == JFileChooser.APPROVE_OPTION) {
            file = fileChooser.getSelectedFile();
            try {
                ImageIO.write(bufferedImage, "jpg", file);
            } catch (IOException ex) {
                Logger.getLogger(FXMLDocumentController.class.getName()).log(Level.SEVERE, null, ex);
            }       
        }
        
    }
    
    @FXML
    private void handleSaveAllButtonAction(ActionEvent event){
        String artistName = albums.get(currentAlbum).getArtistName();
        new File("./images/" + artistName).mkdirs();
        for(Album album : albums){
            Image coverImage = new Image(album.getImageURL());   
            BufferedImage bufferedImage = SwingFXUtils.fromFXImage(coverImage, null);
            
            File file = new File("./images/" + artistName + "/"+ album.getAlbumName() + ".png");
            try {
                ImageIO.write(bufferedImage, "png", file);
                } catch (IOException ex) {
                Logger.getLogger(FXMLDocumentController.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
    
    @FXML
    private void handlePlayButtonAction(ActionEvent event) {
        playOrPauseMusic();
    }

    @FXML
    private void handlePreviousButtonAction(ActionEvent event) {
        // TODO = Display the previous album data
        //        (wrap around if you get below 0)
        currentAlbum--;
        if(currentAlbum < 0)
            currentAlbum = albums.size() - 1;
        displayAlbum(currentAlbum);
    }

    @FXML
    private void handleNextButtonAction(ActionEvent event) {
        // TODO = Display the next album data
        //        (wrap around if you get below 0)
        currentAlbum++;
        if(currentAlbum > albums.size()-1)
            currentAlbum = 0;
        displayAlbum(currentAlbum);
    }
    
    @FXML
    private void handleSearchButtonAction(ActionEvent event) {
        
        progress.setVisible(true);
        searchArtistTextField.setDisable(true);
        previousAlbumButton.setDisable(true);
        nextAlbumButton.setDisable(true);

        // This insures we don't block the GUI when executing slow Web Requests
        ExecutorService executor = Executors.newSingleThreadScheduledExecutor();
        executor.submit(new Task<Void>(){
            @Override
            protected Void call(){
                // You can't change any JavaFX Controls here...
                searchArtist(searchArtistTextField.getText());
                return null;
            }

            @Override
            protected void succeeded() {
                progress.setVisible(false);
                searchArtistTextField.setDisable(false);

                if (albums.size() > 0)
                {
                    displayAlbum(0);
                }
            }

            @Override
            protected void cancelled() { 
                progress.setVisible(false);
                searchArtistTextField.setDisable(false);

                artistLabel.setText("Error!");
                albumLabel.setText("Error retrieving " + searchArtistTextField.getText());
            }
        });
    }
    
    @FXML
    private void handledTrackSliderMouseDrag(MouseEvent event){
        setTrackTime();
    }
    
    @FXML
    private void handledTrackSliderMouseClicked(MouseEvent event){
        setTrackTime();
        if(playButton.getText().equals("Pause"))
            playOrPauseMusic();
      
    }
    
     @FXML
    private void handledTrackSliderMouseReleased(MouseEvent event){
        mediaPlayer.seek(new Duration(trackSlider.getValue() * 1000));
        playOrPauseMusic();
    }
    
    private void displayAlbum(int albumIndex)
    {
        if (albumIndex >=0 && albumIndex < albums.size())
        {
            Album album = albums.get(albumIndex);
            System.out.println(album);
            
            artistLabel.setText(album.getArtistName());
            albumLabel.setText(album.getAlbumName());

            // Set tracks
            ArrayList<TrackForTableView> tracks = new ArrayList<>();
            for (int i=0; i<album.getTracks().size(); ++i)
            {
                TrackForTableView trackForTable = new TrackForTableView();
                Track track = album.getTracks().get(i);
                trackForTable.setTrackNumber(track.getNumber());
                trackForTable.setTrackTitle(track.getTitle());
                trackForTable.setTrackPreviewUrl(track.getUrl());
                tracks.add(trackForTable);
            }
            tracksTableView.setItems(new ObservableListWrapper(tracks));            
                        
            // Previous and next buttons
            if (albums.size() > 1)
            {
                previousAlbumButton.setDisable(false);
                nextAlbumButton.setDisable(false);
            }
            else
            {
                previousAlbumButton.setDisable(true);
                nextAlbumButton.setDisable(true);
            }

            // Cover image
            Image coverImage = new Image(album.getImageURL());
            albumCoverImageView.setImage(coverImage);
            
            // Track 1 slider / time information
            int minutes = album.getTracks().get(0).getDurationInSeconds() / 60;
            int seconds = album.getTracks().get(0).getDurationInSeconds() % 60;
            String secondsStr = seconds < 10 ? "0" + seconds : "" + seconds;
            
            trackSlider.setValue(0.0);
            trackTimeLabel.setText("0.00 / " + minutes + ":" + secondsStr);            
        }
    }
    
    private void searchArtist(String artistName)
    {
        //TODO: when error occurs do something
        try{
            String artistId = SpotifyController.getArtistId(artistName);


            System.out.println("artistId: " + artistId);
            currentAlbum = 0;
            albums = SpotifyController.getAlbumDataFromArtist(artistId); 
        }catch(Exception e){
            handleExceptionOnDifferentThread(e);
        }
    }
    
    public void handleExceptionOnDifferentThread(Exception e){
        handleException(e);
        artistLabel.notify();
        albumLabel.notify();
    }
    
    public void handleException(Exception e){
        Platform.runLater(new Runnable() {
                @Override
                public void run() {
                    artistLabel.setText("Error");    
                    albumLabel.setText(e.getClass().getSimpleName());
                    progress.setVisible(false);
                    searchArtistTextField.setDisable(false);              
                }
        });
        
    }
    
    private void setTrackTime(){
        int totalSeconds = (int) trackSlider.getValue();
        int minutes = totalSeconds / 60;
        int seconds = totalSeconds % 60;
        String secondsStr = "" + seconds;
        
        String totalTime = trackTimeLabel.getText().split("/")[1];
        if(seconds / 10 < 1)
            secondsStr = "0" + seconds;
        
        trackTimeLabel.setText(minutes + ":" + secondsStr + " /" + totalTime);
        
    }
    
    Timer timer = new Timer();
    
    private void playOrPauseMusic(){
        try{
            if (playButton.getText().equals("Play"))
            {
               playMusic();
            }
            else
            {   
                timer.cancel();
                timer = new Timer();

                mediaPlayer.pause();
                playButton.setText("Play");
            }
        }catch(Exception e){
            handleException(e);
        }
    }
    
    public void playMusic(){
        mediaPlayer.play();
        playButton.setText("Pause");
        timer.scheduleAtFixedRate(new TimerTask() {
            @Override
            public void run() {
                Platform.runLater(() -> {
                    trackSlider.setValue(trackSlider.getValue() + 1);
                    setTrackTime();
                });

            }}, 0, 1000);
    }
    @Override
    public void initialize(URL url, ResourceBundle rb) {
        // Setup Table View
        TableColumn<TrackForTableView, Number> trackNumberColumn = new TableColumn("#");
        trackNumberColumn.setCellValueFactory(new PropertyValueFactory("trackNumber"));
        
        TableColumn trackTitleColumn = new TableColumn("Title");
        trackTitleColumn.setCellValueFactory(new PropertyValueFactory("trackTitle"));
        trackTitleColumn.setPrefWidth(250);
        
        TableColumn playColumn = new TableColumn("Preview");
        playColumn.setCellValueFactory(new PropertyValueFactory("trackPreviewUrl"));
        Callback<TableColumn<TrackForTableView, String>, TableCell<TrackForTableView, String>> cellFactory = new Callback<TableColumn<TrackForTableView, String>, TableCell<TrackForTableView, String>>(){
            @Override
            public TableCell<TrackForTableView, String> call(TableColumn<TrackForTableView, String> param) {
                final TableCell<TrackForTableView, String> cell = new TableCell<TrackForTableView, String>(){
                    final Button playButton = new Button("Play");

                    @Override
                    public void updateItem(String item, boolean empty)
                    {
                        if (item != null && item.equals("") == false)
                        {
                            playButton.setOnAction(event -> {
                                if (mediaPlayer != null)
                                {
                                    mediaPlayer.stop();                                    
                                }
                                
                                Media music = new Media(item);
                                mediaPlayer = new MediaPlayer(music);    
                                
                                trackSlider.setValue(0.0);
                                timer.cancel();
                                timer = new Timer();
                                playMusic();
                               
                            });
                            
                            
                            setGraphic(playButton);
                        }
                        else
                        {                        
                            setGraphic(null);
                        }

                        setText(null);
                        
                    }
                };
                
                return cell;
            }
        };
        playColumn.setCellFactory(cellFactory);
        
        tracksTableView.getColumns().setAll(trackNumberColumn, trackTitleColumn, playColumn);
        
        searchArtist("pink floyd");
        displayAlbum(0);
    }        
}
