using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Xna.Framework;
using Microsoft.Xna.Framework.Graphics;

namespace SpaceInvader
{
    public class PhysicsGameObject : GameObject
    {
        public Vector2 velocity;
        protected Vector2 acceleration;
        protected float radius;

        public PhysicsGameObject(Vector2 position, Texture2D sprite,
                                 Vector2 velocity, Vector2 acceleration)
        : base(position, sprite)
        {
            this.velocity = velocity;
            this.acceleration = acceleration;

            // Calculate radius from sprite size
            radius = sprite.Bounds.Width / 2;
        }

        public float GetRadius() { return radius; }
        public override void Update(GameTime gameTime)
        {
            base.Update(gameTime);

            // Apply Gravity and Update Velocity and Position from Acceleration
            float dt = (float)gameTime.ElapsedGameTime.TotalSeconds;

            // Determine new Velocty and Position
            velocity += acceleration * dt;
            position += velocity * dt;

        }
    }
}
