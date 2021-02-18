using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;
using Microsoft.Xna.Framework.Input;
using Microsoft.Xna.Framework.Audio;
using Microsoft.Xna.Framework.Media;

using System;
using System.Collections.Generic;

namespace SpaceInvader
{
    /// <summary>
    /// This is the main type for your game.
    /// </summary>
    public class Game1 : Game
    {
        GraphicsDeviceManager graphics;
        SpriteBatch spriteBatch;
        //Singleton
        private static Game1 instance = null;

        Texture2D spaceShipSprite;
        Texture2D projectileSprite;
        Texture2D enemySprite;
        Texture2D enemyFlashSprite;
        public Texture2D enemyProjectileSprite;



        List<GameObject> gameObjects = new List<GameObject>();

        Song gameMusic;
        Song successMusic;
        SoundEffect cannonSound;
        SoundEffect explosionSound;
        SoundEffect laserSound;

       

        SpriteFont gameFont;

        //---------------Games -------------------
        bool gameIsFinished;
        public bool gameStart = false;
        int gameWidth = 1000;
        int gameHeight = 1000;
        public int score = 0;
        public bool win = false;

        //------------- Lives -----------------------
        public int numOfLives = 3;
        int livesPosX = 800;
        int xBetweenLives = 55;

        //------------ Enemies ----------------------
        int enemiesPerRow = 12;
        int rows = 3;
        int numOfEnemies;
        public bool changeDirection = false;
        public bool goDown = false;


        public Game1()
        {
            

            graphics = new GraphicsDeviceManager(this);
            IsMouseVisible = true;
            Content.RootDirectory = "Content";
            instance = this;
            gameIsFinished = false;

            graphics.PreferredBackBufferWidth = gameWidth;
            graphics.PreferredBackBufferHeight = gameHeight;
            graphics.ApplyChanges();

            numOfEnemies = enemiesPerRow * rows;
        }

        public int getGameWidth() { return gameWidth;}

        public int getGameHeight() { return gameHeight; }


        public static Game1 GetInstance()
        {
            return instance;
        }

        public Texture2D getLaserSprite()
        {
            return projectileSprite;
        }

        public void AddGameObject(GameObject toAdd)
        {
            gameObjects.Add(toAdd);
        }

        public List<GameObject> getGameObjects()
        {
            return gameObjects;
        }
        /// <summary>
        /// Allows the game to perform any initialization it needs to before starting to run.
        /// This is where it can query for any required services and load any non-graphic
        /// related content.  Calling base.Initialize will enumerate through any components
        /// and initialize them as well.
        /// </summary>
        protected override void Initialize()
        {
            // TODO: Add your initialization logic here

            base.Initialize();
        }

        /// <summary>
        /// LoadContent will be called once per game and is the place to load
        /// all of your content.
        /// </summary>
        protected override void LoadContent()
        {
            // Create a new SpriteBatch, which can be used to draw textures.
            spriteBatch = new SpriteBatch(GraphicsDevice);

            // TODO: use this.Content to load your game content here
            spaceShipSprite = Content.Load<Texture2D>("images/SpaceShip");
            projectileSprite = Content.Load<Texture2D>("images/Projectile");
            enemySprite = Content.Load<Texture2D>("images/Enemy");
            enemyFlashSprite = Content.Load<Texture2D>("images/EnemyFlash");
            enemyProjectileSprite = Content.Load<Texture2D>("images/EnemyProjectile");

            gameMusic = Content.Load<Song>("Audio/gameMusic");
            successMusic = Content.Load<Song>("Audio/successMusic");
            cannonSound = Content.Load<SoundEffect>("Audio/cannonSound");
            explosionSound = Content.Load<SoundEffect>("Audio/explosion");
            laserSound = Content.Load<SoundEffect>("Audio/Laser_Cannon-Mike_Koenig-797224747");

            gameFont = Content.Load<SpriteFont>("fonts/gameFont");

            MediaPlayer.Play(gameMusic);

            //--------------------------- Enemies ----------------------------------------


            int xStartPoint = 20, yStartPoint = 20;
            int enemyWidth = 40, enemyHeight = 40;
            int xBetweenEnemies = enemyWidth+ 10;
            int yBetweenEnemies = enemyHeight + 10;

            for(int i = 0; i < rows; i++)
            {
                for(int j = 0; j < enemiesPerRow; j++)
                {
                    gameObjects.Add(new Enemy(
                                    new Vector2(xStartPoint + xBetweenEnemies * j, yStartPoint + yBetweenEnemies * i),
                                    enemySprite, 
                                    enemyFlashSprite,
                                    explosionSound));
                }
            }

            //-------------------------- SpaceShip ---------------------------------------
            int enemiesWidth = xStartPoint + (xBetweenEnemies * enemiesPerRow);
            int enemiesHeight = yStartPoint + (yBetweenEnemies * rows);

            
            gameObjects.Add(new SpaceShip(new Vector2(100, 700), spaceShipSprite, laserSound));

            //-------------------------- Lives -------------------------------------------
            for(int i=0; i<numOfLives; i++)
            {
                gameObjects.Add(new Live(new Vector2(livesPosX + i * xBetweenLives, 800), spaceShipSprite));

            }


        }

        /// <summary>
        /// UnloadContent will be called once per game and is the place to unload
        /// game-specific content.
        /// </summary>
        protected override void UnloadContent()
        {
            // TODO: Unload any non ContentManager content here
        }


        /// <summary>
        /// Allows the game to run logic such as updating the world,
        /// checking for collisions, gathering input, and playing audio.
        /// </summary>
        /// <param name="gameTime">Provides a snapshot of timing values.</param>
        protected override void Update(GameTime gameTime)
        {
            if (Keyboard.GetState().IsKeyDown(Keys.Space))
                gameStart = true;
            
            if (GamePad.GetState(PlayerIndex.One).Buttons.Back == ButtonState.Pressed || Keyboard.GetState().IsKeyDown(Keys.Escape))
                Exit();

            int rnd = (new Random()).Next(1, gameObjects.Count * 5);
            // TODO: Add your update logic here

            for (int i = 0; i < gameObjects.Count; i++)
            {
                GameObject go = gameObjects[i];
                go.Update(gameTime);

                if (go is Enemy)
                {
                    Enemy enemy = (Enemy)go;
                    if(i == rnd)
                        enemy.shoot();
                }
            }



            if (gameIsFinished == false && isGameFinished())
            {
                gameIsFinished = true;

                MediaPlayer.Stop();
                MediaPlayer.Play(successMusic);
            }



            base.Update(gameTime);
        }

        /// <summary>
        /// This is called when the game should draw itself.
        /// </summary>
        /// <param name="gameTime">Provides a snapshot of timing values.</param>
        protected override void Draw(GameTime gameTime)
        { 
            GraphicsDevice.Clear(Color.White);

            // TODO: Add your drawing code here
            spriteBatch.Begin();

            int enemyLeft = 0;
            for(int i = 0; i < gameObjects.Count; i++)
            {
                if (gameObjects[i] is Enemy)
                    enemyLeft++;
            }
            spriteBatch.DrawString(gameFont, "Score: " + score, new Vector2(50, 800), Color.Black);

            if (!gameStart)
            {
                spriteBatch.DrawString(gameFont, "Press 'Space' to Start game", new Vector2(300, 200), Color.Black);
            }

            else
            {
                foreach (GameObject gameObject in gameObjects)
                {
                    gameObject.Draw(spriteBatch, gameTime);
                }
            }
            

            

            if (gameIsFinished)
            {
                for(int i = 0; i < gameObjects.Count; i++)
                {
                    if (gameObjects[i] is SpaceShip)
                        gameObjects.Remove(gameObjects[i]);
                }
                if(numOfLives > 0)
                    spriteBatch.DrawString(gameFont, "Victory", new Vector2(500, 200), Color.Black);
                if(numOfEnemies > 0)
                    spriteBatch.DrawString(gameFont, "Game Over", new Vector2(500, 200), Color.Black);

            }





            spriteBatch.End();
            base.Draw(gameTime);
        }

        public bool isGameFinished()
        {
            numOfEnemies = 0;
            numOfLives = 0;
            for(int i = 0; i < gameObjects.Count; i++)
            {
                if (gameObjects[i] is Enemy)
                    numOfEnemies++;

                if (gameObjects[i] is Live)
                    numOfLives++;

            }

            //Victory
            if (numOfLives > 0 && numOfEnemies == 0)
                return true;
            //Game over
            if (numOfLives == 0 && numOfEnemies > 0)
                return true;

            return false;
        }
    }
}
