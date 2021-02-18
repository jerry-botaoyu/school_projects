package spotifyplayer;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Base64;
import java.util.logging.Level;
import java.util.logging.Logger;


public class SpotifyController{
    final static private String SPOTIFY_CLIENT_ID     = "cd0a1b2701a74c80add30cf21ded2779";
    final static private String SPOTIFY_CLIENT_SECRET = "9d5f67fd3b4f408fae659b8d51709874";
    
    public static String getArtistId(String artistNameQuery) throws ProtocolException, IOException
    {
        String artistId = "";
        
            // TODO - From an artist string, get the spotify ID
            // Recommended Endpoint: https://api.spotify.com/v1/search
            // Parse the JSON output to retrieve the ID
        
            String endpoint = "https://api.spotify.com/v1/search";
            String params = "type=artist&q=" + artistNameQuery;
            String jsonOutput = spotifyEndpointToJson(endpoint, params);
            
            JsonObject root = new JsonParser().parse(jsonOutput).getAsJsonObject();
            JsonObject artists = root.get("artists").getAsJsonObject();
            JsonArray items = artists.get("items").getAsJsonArray();
            
            if(items.size() > 0){
                JsonObject item = items.get(0).getAsJsonObject();
                String id = item.get("id").getAsString();
                
                return id;
            }else{
                return null;
            }
            
            // ID for the beatles... replace with value from Json output
            //artistId = "3WrFJ7ztbogyGnTHbHJFl2";

    }
    
    public static ArrayList<String> getAlbumIdsFromArtist(String artistId) throws ProtocolException, IOException
    {
        ArrayList<String> albumIds = new ArrayList<>();

            // TODO - Retrieve album ids from an artist id
            // Recommended endpoint {id} is the id of the artist in parameter: 
            //             https://api.spotify.com/v1/artists/{id}/albums
            //
            // Arguments - Filter for the CA market, and limit to 50 albums
            String endPoint = "https://api.spotify.com/v1/artists/" + artistId + "/albums";
            String params = "market=CA";
            String jsonOutput = spotifyEndpointToJson(endPoint, params);
            JsonObject root = new JsonParser().parse(jsonOutput).getAsJsonObject();
            JsonArray albumItems = root.getAsJsonArray("items");
            int numOfAlbum = (albumItems.size() < 50) ? albumItems.size() : 50;
            
            for(int i = 0; i <= numOfAlbum -1; i++){
                JsonObject albumItem = albumItems.get(i).getAsJsonObject();
                String id = albumItem.get("id").getAsString();
                albumIds.add(id);
            }
        
        return albumIds;
    }
    
    public static ArrayList<Album> getAlbumDataFromArtist(String artistId) throws ProtocolException, IOException
    {
        ArrayList<String> albumIds = getAlbumIdsFromArtist(artistId);
        ArrayList<Album> albums = new ArrayList<>();
        
        for(String albumId : albumIds)
        {
           
            // TODO - Retrieve all album data from the list of album ids for an artist
            // 
            // You can have a look at the Album class included
            // 
            // Endpoint : https://api.spotify.com/v1/albums/{id}
            // Note:      {id} is the id of the album
            //
            // Arguments - Filter for the CA market

            String endPoint = "https://api.spotify.com/v1/albums/" + albumId;
            String params = "market=CA";
            String jsonOutput = spotifyEndpointToJson(endPoint, params);

            JsonObject root = new JsonParser().parse(jsonOutput).getAsJsonObject();
            String artistName = root.getAsJsonArray("artists").get(0).getAsJsonObject()
                                .get("name").getAsString();
            String coverURL = root.getAsJsonArray("images").get(0).getAsJsonObject()
                                .get("url").getAsString();
            String albumName = root.get("name").getAsString();

            JsonArray tracksItems = root.getAsJsonObject("tracks").getAsJsonArray("items");
            ArrayList<Track> albumTracks = new ArrayList<>();

            for(int i = 0; i < tracksItems.size(); i++){
                // Warning!! For the preview_url, the json item can be a string 
            //           or null, below is the code to write for parsing
                JsonObject item = tracksItems.get(i).getAsJsonObject();
                String previewUrl = "";

                if (item.get("preview_url").isJsonNull() == false)
                    previewUrl = item.get("preview_url").getAsString();

                String name = item.get("name").getAsString();
                int duration = 30;

                albumTracks.add(new Track(i, name, duration, previewUrl));

            }

            albums.add(new Album(artistName, albumName,  coverURL, albumTracks));                
                       
        }
        
        return albums;
    }
    


    // This code will help you retrieve the JSON data from a spotify end point
    // It takes care of the complicated parts such as the authentication and 
    // connection to the Web API
    // 
    // You shouldn't have to modify any of the code...
    private static String spotifyEndpointToJson(String endpoint, String params) throws MalformedURLException, ProtocolException, IOException
    {
        params = params.replace(' ', '+');

        
        String fullURL = endpoint;
        if (params.isEmpty() == false)
        {
            fullURL += "?"+params;
        }

        URL requestURL = new URL(fullURL);

        HttpURLConnection connection = (HttpURLConnection)requestURL.openConnection();
        String bearerAuth = "Bearer " + getSpotifyAccessToken();
        connection.setRequestProperty ("Authorization", bearerAuth);
        connection.setRequestMethod("GET");

        BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()));

        String inputLine;
        String jsonOutput = "";
        while((inputLine = in.readLine()) != null)
        {
            jsonOutput += inputLine;
        }
        in.close();

        return jsonOutput;

    }


    // This implements the Client Credentials Authorization Flows
    // Based on the Spotify API documentation
    // 
    // It retrieves the Access Token based on the client ID and client Secret  
    //
    // You shouldn't have to modify any of this code...          
    private static String getSpotifyAccessToken() throws MalformedURLException, IOException
    {
        URL requestURL = new URL("https://accounts.spotify.com/api/token");

        HttpURLConnection connection = (HttpURLConnection)requestURL.openConnection();
        String keys = SPOTIFY_CLIENT_ID+":"+SPOTIFY_CLIENT_SECRET;
        String postData = "grant_type=client_credentials";

        String basicAuth = "Basic " + new String(Base64.getEncoder().encode(keys.getBytes()));

        // Send header parameter
        connection.setRequestProperty ("Authorization", basicAuth);

        connection.setRequestMethod("POST");
        connection.setDoOutput(true);

        // Send body parameters
        OutputStream os = connection.getOutputStream();
        os.write( postData.getBytes() );
        os.close();

        BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()));

        String inputLine;
        String jsonOutput = "";
        while((inputLine = in.readLine()) != null)
        {
            jsonOutput += inputLine;
        }
        in.close();

        JsonElement jelement = new JsonParser().parse(jsonOutput);
        JsonObject rootObject = jelement.getAsJsonObject();
        String token = rootObject.get("access_token").getAsString();

        return token;

    }
}
