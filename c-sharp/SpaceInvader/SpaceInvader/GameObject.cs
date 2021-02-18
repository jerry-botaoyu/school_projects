using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;

namespace SpaceInvader
{
    public class GameObject
    {
        public Vector2 position;
        protected Texture2D sprite;
        protected float rotationAngle;
        protected Color color;

        public GameObject(Vector2 position, Texture2D sprite)
        {
            this.position = position;
            this.sprite = sprite;
            this.rotationAngle = 0.0f;
            this.color = Color.White;
        }

        public virtual void Update(GameTime gameTime){ /*TO BE OVERRIDE */}

        public virtual void Draw(SpriteBatch spriteBatch, GameTime gameTime)
        {
            // Make sure the position of the sprite is at the center of where it draws
            Vector2 spriteCenter = new Vector2(sprite.Bounds.Center.X, sprite.Bounds.Center.Y);
            Vector2 offsetPosition = position - spriteCenter;
            spriteBatch.Draw(sprite, position, null, color, rotationAngle, spriteCenter, 1.0f, SpriteEffects.None, 0f);
        }

        public Vector2 GetPosition()
        {
            return position;
        }

        public void SetPosition(float x, float y)
        {
            position = new Vector2(x, y);
        }

        
    }
}
